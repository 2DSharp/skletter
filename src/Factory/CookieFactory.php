<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Factory;


use Skletter\Component\SecureTokenManager;
use Skletter\Model\RemoteService\DTO\CookieDTO;
use Symfony\Component\HttpFoundation\Cookie;

class CookieFactory
{
    /**
     * Generates a signed Symfony Cookie based on a cookie DTO model
     * @param CookieDTO $identity
     * @param string $name
     * @param string $userAgent
     * @return Cookie
     */
    public static function createSignedCookie(CookieDTO $identity, string $name, string $userAgent): Cookie
    {
        $cookieData = $identity->id . ":" . $identity->token;
        $signature = SecureTokenManager::signCookie($cookieData, $userAgent);

        $signedCookie = $cookieData . ":" . $signature;

        return Cookie::create($name, $signedCookie,
                              \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC1123,
                                                                   $identity->expiry));
    }
}