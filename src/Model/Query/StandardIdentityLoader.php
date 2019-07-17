<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Query;


use PDO;
use Skletter\Model\Entity\StandardIdentity;

abstract class StandardIdentityLoader
{
    /**
     * @var PDO $connection
     */
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    private function fetchData(string $query, string $bindParam, string $value): array
    {
        /**
         * @var \PDOStatement $stmt
         */
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue($bindParam, $value);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    protected function load(StandardIdentity $identity, string $query, string $bindParam, string $value): bool
    {
        $data = $this->fetchData($query, $bindParam, $value);

        if (empty($data) === false) {
            $identity->setId($data['ID']);
            $identity->setHashedPassword($data['HashedPassword']);
            return true;
        }
    }

    protected function find(string $query, string $bindParam, string $value): bool
    {
        $data = $this->fetchData($query, $bindParam, $value);
        return (empty($data) === false);
    }
}