<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Contract\Repository;


use Skletter\Contract\Entity\Identity;

/**
 * This contract specifies that an IdentityRepository must be able to do the following operations:
 *
 * CREATE
 * UPDATE
 * DELETE
 *
 * The repository provides generic methods to mutate data on an entity and persist it using a data mapper.
 *
 * Interface IdentityRepositoryInterface
 * @package Skletter\Contract\Repository
 */
interface IdentityRepositoryInterface
{
    function save(Identity $identity);

    function update(Identity $identity);

    function delete(Identity $identity);

    function has(Identity $identity);
}