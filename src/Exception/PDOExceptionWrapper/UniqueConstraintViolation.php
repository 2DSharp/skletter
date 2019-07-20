<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Exception\PDOExceptionWrapper;


class UniqueConstraintViolation extends \PDOException
{
    public function __construct($message = null, $code = null)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function getOffendingField(): string
    {
        return str_replace("'", "", $this->findLastWord($this->message));
    }

    private function findLastWord(string $msg): string
    {
        $pieces = explode(' ', $msg);
        $last_word = array_pop($pieces);

        return $last_word;
    }
}