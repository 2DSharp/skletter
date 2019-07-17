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
use Skletter\Model\Entity\NonceIdentity;

class NonceIdentityMapper extends IdentityMapper
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
     * @param Identity $identity
     * @param array $fields
     * @throws RecordNotFound
     */
    public function fetch(Identity $identity, array $fields): void
    {
        // TODO: Implement fetch() method.
    }

    /**
     * Persist a generated nonce identity for Recovery Codes/New registration
     * @param Identity $identity
     * @return bool
     */
    public function store(Identity $identity): bool
    {
        /**
         * @var NonceIdentity $identity
         */
        $command = /** @lang MySQL */
            "INSERT INTO NonceIdentity (IdentityID, Token, Pin, ValidTill) VALUES (:id, :token, :pin, :validity)";
        $statement = $this->connection->prepare($command);

        $statement->bindValue(':id', $identity->getId());
        $statement->bindValue(':token', $identity->getToken());
        $statement->bindValue(':pin', $identity->getPin());
        $statement->bindValue(':validity', $identity->getValidTill());

        $statement->execute();
    }
}