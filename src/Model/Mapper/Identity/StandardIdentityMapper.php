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


use Skletter\Contract\Mapper\DataMapper;
use Skletter\Model\Entity\StandardIdentity;

class StandardIdentityMapper implements DataMapper
{
    /**
     * @var \PDO $connection
     */
    private $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Query to fetch entity data
     *
     * @param StandardIdentity $entity
     * @param null $queryObject
     */
    public function fetch($entity, $queryObject = null): void
    {
        // TODO: Implement fetch() method.
    }

    /**
     * @param StandardIdentity $entity
     * @param null $queryObject
     * @return bool
     */
    public function update($entity, $queryObject = null): bool
    {
        // TODO: Implement update() method.
    }

    public function delete($entity, $queryObject = null): bool
    {
        // TODO: Implement delete() method.
    }

    public function store($entity, $queryObject = null): bool
    {
        // TODO: Implement store() method.
    }

}