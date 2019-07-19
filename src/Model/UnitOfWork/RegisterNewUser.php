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


use Skletter\Component\SensiblePDOExceptionBuilder;
use Skletter\Contract\Repository\IdentityRepositoryInterface;
use Skletter\Contract\Transaction;
use Skletter\Model\Entity\NonceIdentity;
use Skletter\Model\Entity\Profile;
use Skletter\Model\Entity\StandardIdentity;
use Skletter\Model\Mapper\ProfileMapper;

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
     * @var ProfileMapper $profileMapper
     */
    private $profileMapper;
    /**
     * @var Profile $profile
     */
    private $profile;

    /**
     * RegisterNewUser constructor.
     * @param \PDO $pdo
     * @param IdentityRepositoryInterface $identityRepository
     * @param ProfileMapper $profileMapper
     */
    public function __construct(\PDO $pdo, IdentityRepositoryInterface $identityRepository, ProfileMapper $profileMapper)
    {
        $this->connection = $pdo;
        $this->identityRepository = $identityRepository;
        $this->profileMapper = $profileMapper;
    }

    public function registerIdentity(StandardIdentity $identity, NonceIdentity $nonce)
    {
        $this->identity = $identity;
        $this->nonce = $nonce;
    }

    public function registerProfile(Profile $profile)
    {
        $this->profile = $profile;
    }

    public function commit(): bool
    {
        try {
            $this->connection->beginTransaction();

            $this->identityRepository->save($this->identity);

            // Transfer the id
            $this->nonce->setId($this->identity->getId());
            $this->identityRepository->save($this->nonce);

            $this->profile->setIdentity($this->identity);
            $this->profileMapper->store($this->profile);

            return $this->connection->commit();
        } catch (\PDOException $exception) {
            $this->connection->rollBack();
            $exceptionBuilder = new SensiblePDOExceptionBuilder($exception);
            $exceptionBuilder->throw();
        }

    }

    public function rollBack()
    {

    }
}