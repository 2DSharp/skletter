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


class UserFriendlyError
{
    public const EMAIL_ALREADY_REGISTERED = 0;
    public const USERNAME_ALREADY_REGISTERED = 1;
    public const NONEXISTENT_IDENTIFIER = 2;
    public const INVALID_PASSWORD = 3;

    private static $errors =
        [
            self::EMAIL_ALREADY_REGISTERED => 'The email you provided has already been used.Perhaps you\'d like to log in instead?',
            self::USERNAME_ALREADY_REGISTERED => "The username you provided has already been used. Perhaps you'd like to log in instead?",
            self::NONEXISTENT_IDENTIFIER => 'The username or email you have entered does not belong to any account.',
        ];

    public static function getError(int $err): string
    {
        return self::$errors[$err];
    }

}