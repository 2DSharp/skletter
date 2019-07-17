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


use Skletter\Component\QueryBuilder\QueryBuilderInterface;
use Skletter\Contract\Entity\Identity;
use Skletter\Exception\Mapper\RecordNotFound;
use Skletter\Model\Entity\StandardIdentity;

class StandardIdentityMapper extends IdentityMapper
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
     * @param StandardIdentity $identity
     * @param array $fields
     * @return mixed
     */
    private function getFetchedData(StandardIdentity $identity, array $fields)
    {
        if ($identity->getId()) {
            return $this->fetchByID($identity, $fields);
        }
        return $this->fetchByIdentifier($identity, $fields);
    }

    /**
     * @param Identity $identity
     * @param array $fields
     * @throws RecordNotFound
     */
    public function fetch(Identity $identity, array $fields): void
    {
        /** @var StandardIdentity $identity */

        $data = $this->getFetchedData($identity, $fields);

        if (empty($data) === true) {
            throw new RecordNotFound();
        }
        $this->setFetchedData($identity, $data);
    }

    public function store(Identity $identity): bool
    {
        /**
         * @var StandardIdentity $identity
         */
        $command = /** @lang MySQL */
            "INSERT INTO Identity (Email, Username, HashedPassword, Status) VALUES (:email, :username, :passwordHash, :currStatus)";
        $statement = $this->connection->prepare($command);

        $statement->bindValue(':email', $identity->getEmail());
        $statement->bindValue(':username', $identity->getUsername());
        $statement->bindValue(':passwordHash', $identity->getHashedPassword());
        $statement->bindValue(':currStatus', $identity->getStatus());

        $statement->execute();

        $identity->setId((int)$this->connection->lastInsertId());
    }
    /**
     * @param Identity $identity
     * @return bool
     */
    public function exists(Identity $identity): bool
    {
        /** @var StandardIdentity $identity */
        $data = $this->getFetchedData($identity, [1]);
        return (empty($data) === false);
    }

    /**
     * @param StandardIdentity $identity
     * @param array $fields
     * @return mixed
     */
    public function fetchByID(StandardIdentity $identity, array $fields)
    {
        $fields = implode(",", $fields);
        $query = /** @lang MySQL */
            "SELECT {$fields} FROM Identity WHERE ID = :id";

        return $this->retrieveResults($query, ':id', $identity->getId());
    }

    private function buildQueryForIdentifier(int $type, array $fields, string $placeholder)
    {
        $fields = implode(",", $fields);

        $map = [
            StandardIdentity::EMAIL => /** @lang MySQL */
                "SELECT {$fields} FROM Identity WHERE Email = {$placeholder}",
            StandardIdentity::USERNAME => /** @lang MySQL */
                "SELECT {$fields} FROM Identity WHERE Username = {$placeholder}"
        ];

        return $map[$type];
    }

    /**
     * @param StandardIdentity $identity
     * @param array $fields
     * @return mixed
     */
    private function fetchByIdentifier(StandardIdentity $identity, array $fields)
    {
        $query = $this->buildQueryForIdentifier($identity->getType(), $fields, ':identifier');
        $data = $this->retrieveResults($query, ':identifier', $identity->getIdentifier());

        return $data;
    }

    private function retrieveResults(string $query, string $placeholder, $value)
    {
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue($placeholder, $value);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Run the setters on the entity
     * @param StandardIdentity $entity
     * @param array $data
     */
    private function setFetchedData(StandardIdentity $entity, array $data)
    {
        foreach ($data as $key => $value) {
            $setter = "set" . $key;
            $entity->{$setter}($value);
        }
    }

    /**
     * @todo Implement proper query builder
     * @deprecated
     * Fetch the data from DB to an array
     * @param QueryBuilderInterface $queryBuilder
     * @return mixed
     */
    private function getDataFromData(QueryBuilderInterface $queryBuilder)
    {
        $fields = $queryBuilder->getFieldsAsString();
        $filters = $queryBuilder->getFiltersAsString();

        $query = /** @lang MySQL */
            "SELECT {$fields} FROM Identity WHERE {$filters}";

        $stmt = $this->connection->prepare($query);

        foreach ($queryBuilder->getFilters() as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}