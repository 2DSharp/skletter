<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Entity;


class User
{
    protected int $id;
    protected string $name;
    protected string $username;

    /**
     * User constructor.
     * @param int $id
     * @param string $name
     * @param string $username
     */
    public function __construct(int $id, string $name, string $username)
    {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
}