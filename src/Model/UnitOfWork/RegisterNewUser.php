<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\UnitOfWork;


use Skletter\Contract\Repository\IdentityRepositoryInterface;
use Skletter\Contract\Transaction;
use Skletter\Model\Entity\NonceIdentity;
use Skletter\Model\Entity\StandardIdentity;

/**
 * Class RegisterNewUser responsible for one unit of work- Registration of a new user.
 * Transaction will succeed or fail completely and rollback
 * @package Skletter\Model\Transaction
 */
class RegisterNewUser implements Transaction
{
    /**
     * Repository to manipulate Identity
     * @var IdentityRepositoryInterface $identityRepository
     */
    private $identityRepository;
    /**
     * @var StandardIdentity $identity
     */
    private $identity;
    /**
     * @var NonceIdentity $nonce
     */
    private $nonce;
    /**
     * @var \PDO $connection
     */
    private $connection;

    /**
     * RegisterNewUser constructor.
     * @param \PDO $pdo
     * @param IdentityRepositoryInterface $identityRepository
     */
    public function __construct(\PDO $pdo, IdentityRepositoryInterface $identityRepository)
    {
        $this->connection = $pdo;
        $this->identityRepository = $identityRepository;
    }

    public function registerIdentity(StandardIdentity $identity, NonceIdentity $nonce)
    {
        $this->identity = $identity;
        $this->nonce = $nonce;
    }

    public function registerProfile()
    {

    }

    public function commit(): bool
    {
        $this->connection->beginTransaction();

        $this->identityRepository->save($this->identity);

        // Transfer the id
        $this->nonce->setId($this->identity->getId());
        $this->identityRepository->save($this->nonce);
        // Save profile information

        return $this->connection->commit();
    }

    public function rollBack()
    {

    }
}