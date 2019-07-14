<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Contract\Entity;


use Phypes\Type\Type;

interface Identity
{
    function setID(int $id): void;

    function getID(): int;

    function getIdentifier(): Type;


}