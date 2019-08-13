<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Contract\Query;


interface QueryObject
{
    /**
     * @param  $entity
     * @return bool if successfully found the entity in db
     */
    public function find($entity): bool;
}