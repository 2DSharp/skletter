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


use DateTimeImmutable;
use Skletter\Contract\Entity\Identity;
use Skletter\Exception\Mapper\RecordNotFound;
use Skletter\Model\Entity\CookieIdentity;

class CookieIdentityMapper extends IdentityMapper
{
    /**
     * @var \PDO $connection
     */
    private $connection;

    /**
     * CookieIdentityMapper constructor.
     * @param \PDO $connection
     */
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Identity $identity
     * @param array $fields
     * @throws RecordNotFound
     * @throws \Exception
     */
    public function fetch(Identity $identity, array $fields): void
    {
        /** @var CookieIdentity $identity */
        $query = "SELECT IdentityID, ValidTill FROM CookieIdentity WHERE Token = :token";
        $data = $this->bindAndFetch($this->connection, $query, [':token' => $identity->getToken()]);

        if (empty($data) === true) {
            throw new RecordNotFound();
        }
        $this->setFetchedData($identity, $data);
    }

    /**
     * @param CookieIdentity $identity
     * @param $data
     * @throws \Exception
     */
    private function setFetchedData(CookieIdentity $identity, $data)
    {
        $identity->setId($data['IdentityID']);
        $identity->setValidTill(new DateTimeImmutable($data['ValidTill']));
    }

    /**
     * Store cookie data identified by token
     * @param Identity $identity
     * @return bool
     */
    public function store(Identity $identity): bool
    {
        /**
         * @var CookieIdentity $identity
         */
        $command = /** @lang MySQL */
            "INSERT INTO CookieIdentity (IdentityID, Token, ValidTill) VALUES (:id, :token, :validity)";
        $statement = $this->connection->prepare($command);

        $statement->bindValue(':id', $identity->getId());
        $statement->bindValue(':token', $identity->getToken());
        $statement->bindValue(':validity', $identity->getValidTill()->format('Y-m-d h:i:s'));

        return $statement->execute();
    }
}