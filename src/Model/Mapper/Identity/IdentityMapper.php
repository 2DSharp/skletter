<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Mapper\Identity;

use Skletter\Contract\Entity\Identity;
use Skletter\Exception\Mapper\RecordNotFound;

abstract class IdentityMapper
{
    /**
     * @param Identity $identity
     * @param array $fields
     * @throws RecordNotFound
     */
    public abstract function fetch(Identity $identity, array $fields): void;


    public function update($entity): bool
    {
        // TODO: Implement update() method.
    }

    public function delete($entity): bool
    {
        // TODO: Implement delete() method.
    }

    public function store($entity): bool
    {
        // TODO: Implement store() method.
    }
}