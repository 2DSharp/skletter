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
     * @return TokenKeyPair
     */
    public static function generate(): TokenKeyPair
    {
        $token = openssl_random_pseudo_bytes(32);
        $key = openssl_random_pseudo_bytes(16);
        $token .= ':' . hash_hmac('sha256', $token, $key);

        return new TokenKeyPair($token, $key);
    }

    /**
     * Check the hmac to find any tampering
     * @param TokenKeyPair $pair
     * @return bool
     */
    public static function isTampered(TokenKeyPair $pair): bool
    {
        list($tokenValue, $hmac) = explode(':', $pair->getToken(), 2);
        return ($hmac != hash_hmac('sha256', $tokenValue, $pair->getKey()));
    }
}