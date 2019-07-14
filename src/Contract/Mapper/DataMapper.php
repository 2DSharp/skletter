<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Contract\Mapper;


interface DataMapper
{
    /**
     * Query to fetch entity data
     * @param $entity
     */
    public function fetch($entity, $queryObject = null): void;

    // Commands to update, delete and store data
    public function update($entity, $queryObject = null): bool;

    public function delete($entity, $queryObject = null): bool;

    public function store($entity, $queryObject = null): bool;
}