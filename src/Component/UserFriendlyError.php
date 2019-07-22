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
    public const INVALID_PASSWORD_VAGUE = 3;
    const EMPTY_REQUIRED = 4;
    const INVALID_EMAIL_HELPFUL = 5;
    const INVALID_USERNAME_HELPFUL = 6;
    const INVALID_PASSWORD_HELPFUL = 7;

    private static $errors =
        [
            self::EMAIL_ALREADY_REGISTERED => 'The email you provided has already been used. Perhaps you\'d like to log in instead?',
            self::USERNAME_ALREADY_REGISTERED => "The username you provided has already been used. Perhaps you'd like to log in instead?",
            self::NONEXISTENT_IDENTIFIER => 'The username or email you have entered does not belong to any account.',
            self::INVALID_PASSWORD_VAGUE => 'You have entered an incorrect password',
            self::INVALID_EMAIL_HELPFUL => "The email you entered is invalid. Please check your email and try again",
            self::INVALID_USERNAME_HELPFUL => "The username you entered isn't in the correct format. 2-14 characters including alphabets, digits and underscores (_) are allowed",
            self::INVALID_PASSWORD_HELPFUL => "The password must be a minimum of 8 characters with alphanumeric upper case, lower case combination",
            self::EMPTY_REQUIRED => 'You must fill in all the fields'
        ];

    public static function getError(int $err): string
    {
        return self::$errors[$err];
    }
}