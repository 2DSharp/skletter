<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\ValueObject;


class ProfileSuggestion
{
    public string $name;
    public string $username;

    /**
     * ProfileSuggestion constructor.
     * @param string $name
     * @param string $username
     */
    public function __construct(string $name, string $username)
    {
        $this->name = $name;
        $this->username = $username;
    }
}