<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Component;


class SecureTokenManager
{
    /**
     * Generates a secure random cookie string with hmac
     *
     * @param string $token
     * @param string $userAgent
     * @return string
     */
    public static function signCookie(string $token, string $userAgent): string
    {
        $key = base64_decode($_ENV['COOKIE_HMAC_KEY']);
        $token .= '::' . hash_hmac('sha256', $token . $userAgent, $key);

        return $token;
    }

    /**
     * Check the hmac to find any tampering
     *
     * @param  string $token
     * @param string $userAgent
     * @return bool
     */
    public static function isTampered(string $token, string $userAgent): bool
    {
        list($id, $tokenValue, $hmac) = explode('::', $token, 2);
        return ($hmac != hash_hmac('sha256', $tokenValue . $userAgent, base64_decode($_ENV['COOKIE_HMAC_KEY'])));
    }
}