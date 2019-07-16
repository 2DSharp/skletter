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

    private function fetchData(StandardIdentity $identity, string $query, string $bindParam): array
    {
        /**
         * @var \PDOStatement $stmt
         */
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue($bindParam, $identity->getIdentifier()->getValue());
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    protected function load(StandardIdentity $identity, string $query, string $bindParam): bool
    {
        $data = $this->fetchData($identity, $query, $bindParam);

        if (empty($data) === false) {
            $identity->setId($data['ID']);
            $identity->setHashedPassword($data['HashedPassword']);
            return true;
        }
    }

    protected function find(StandardIdentity $identity, string $query, string $bindParam): bool
    {
        $data = $this->fetchData($identity, $query, $bindParam);
        return (empty($data) === false);
    }
}