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
     * @param string $token
     * @param string $userAgent
     * @return string
     */
    private static function signCookie(string $token, string $userAgent): string
    {
        $key = base64_decode($_ENV['COOKIE_HMAC_KEY']);
        return '::' . hash_hmac('sha256', $token . $userAgent, $key);
    }

    /**
     * Check the hmac to find any tampering
     *
     * @param string $tokenValue
     * @param string $hmac
     * @param string $userAgent
     * @return bool
     */
    private static function isTampered(string $tokenValue, string $hmac, string $userAgent): bool
    {
        return ($hmac != hash_hmac('sha256', $tokenValue . $userAgent, base64_decode($_ENV['COOKIE_HMAC_KEY'])));
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
        $cookieData = $identity->id . ":" . $identity->token;
        $signature = self::signCookie($cookieData, $userAgent);

        $signedCookie = $cookieData . ":" . $signature;

        return Cookie::create($name, $signedCookie, new \DateTimeImmutable($identity->expiry));
        /*
         * For production
         * return Cookie::create($name, $signedCookie, new \DateTimeImmutable($identity->expiry), '/', $_ENV['base_url'], true, true);
         *
         */
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
        $userAgent = $extras['User-Agent'];
        list($id, $tokenValue, $hmac) = explode('::', $signedCookieString, 2);

        if (!self::isTampered($tokenValue, $hmac, $userAgent)) {
            $cookieDTO = new CookieDTO();

            $cookieDTO->token = $tokenValue;
            $cookieDTO->id = $id;
            $cookieDTO->expiry = "";

            return $cookieDTO;
        }
        throw new InvalidCookie("The cookie has been tampered with.");
    }
}