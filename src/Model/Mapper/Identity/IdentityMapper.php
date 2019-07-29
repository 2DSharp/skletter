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

    public abstract function store(Identity $identity): bool;


    public function update($entity): bool
    {
        // TODO: Implement update() method.
    }

    public function delete($entity): bool
    {
        // TODO: Implement delete() method.
    }

    protected function bindAndFetch(\PDO $connection, string $query, array $bindValues = [])
    {
        $stmt = $connection->prepare($query);

        foreach ($bindValues as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }

        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}