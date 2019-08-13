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


use Skletter\Component\ValueObject\TokenKeyPair;
use Skletter\Contract\Component\TokenManager;

class SecureTokenManager implements TokenManager
{
    /**
     * Generates a secure random cookie string with hmac
     *
     * @return string
     */
    public static function generate(): string
    {
        $token = openssl_random_pseudo_bytes(32);
        $key = base64_decode($_ENV['COOKIE_HMAC_KEY']);
        $token .= ':' . hash_hmac('sha256', $token, $key);

        return $token;
    }

    /**
     * Check the hmac to find any tampering
     *
     * @param  string $token
     * @return bool
     */
    public static function isTampered(string $token): bool
    {
        list($tokenValue, $hmac) = explode(':', $token, 2);
        return ($hmac != hash_hmac('sha256', $tokenValue, base64_decode($_ENV['COOKIE_HMAC_KEY'])));
    }
}