<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\LocalService;

use Skletter\Exception\InvalidCookie;
use Skletter\Model\RemoteService\Romeo\CookieDTO;
use Symfony\Component\HttpFoundation\Cookie;

class CookieManager
{

    /**
     * Generates a secure random cookie string with hmac
     *
     * @param string $cookieString
     * @return string
     */
    private static function signCookie(string $cookieString): string
    {
        $key = base64_decode($_ENV['COOKIE_HMAC_KEY']);
        return hash_hmac('sha256', $cookieString, $key);
    }

    /**
     * Check the hmac to find any tampering
     *
     * @param string $cookieString
     * @param string $hmac
     * @return bool
     */
    private static function isTampered(string $cookieString, string $hmac): bool
    {
        return hash_hmac('sha256', $cookieString, base64_decode($_ENV['COOKIE_HMAC_KEY'])) == $hmac;
    }

    /**
     * Generates a signed Symfony Cookie based on a cookie DTO model
     * @param CookieDTO $identity
     * @param string $name
     * @param string $userAgent
     * @return Cookie
     * @throws \Exception
     */
    public static function createSignedCookie(CookieDTO $identity, string $name, string $userAgent): Cookie
    {
        $cookieData = self::createCookieString($identity->id, $identity->token, $userAgent);
        $signature = self::signCookie($cookieData);

        $signedCookie = $cookieData . "::" . $signature;

        return Cookie::create($name, $signedCookie, new \DateTimeImmutable($identity->expiry));
        /*
         * For production
         * return Cookie::create($name, $signedCookie, new \DateTimeImmutable($identity->expiry), '/', $_ENV['base_url'], true, true);
         *
         */
    }

    /**
     * @param string $id
     * @param string $token
     * @param string $userAgent
     * @return string
     */
    private static function createCookieString(string $id, string $token, string $userAgent): string
    {
        return $id . "::" . $token;
    }

    /**
     * Generates a CookieDTO out of a raw cookie string, validates authenticity before creating the DTO
     * @param string $signedCookieString
     * @param array $extras
     * @return CookieDTO
     * @throws InvalidCookie
     */
    public static function getLoginCookie(string $signedCookieString, array $extras = []): CookieDTO
    {
        list($id, $tokenValue, $hmac) = explode('::', $signedCookieString, 3);

        $cookieString = self::createCookieString($id, $tokenValue, $extras['User-Agent']);
        if (self::isTampered($cookieString, $hmac)) {
            $cookieDTO = new CookieDTO();
            $cookieDTO->token = $tokenValue;
            $cookieDTO->id = $id;
            $cookieDTO->expiry = "";
            return $cookieDTO;
        } else throw new InvalidCookie("The cookie has been tampered with.");

    }

}