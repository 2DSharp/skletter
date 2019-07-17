<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Service;


use Skletter\Contract\Repository\IdentityRepositoryInterface;
use Skletter\Contract\Transaction;
use Skletter\Exception\IdentifierExistsException;
use Skletter\Exception\ValidationError;
use Skletter\Model\Entity\NonceIdentity;
use Skletter\Model\Entity\StandardIdentity;
use Skletter\Model\UnitOfWork\RegisterNewUser;


class RegistrationManager
{
    /**
     * @var StandardIdentity $identity
     */
    private $identity;
    /**
     * @var NonceIdentity $nonce
     */
    private $nonce;
    /**
     * Mapping service for identity look ups
     * @var IdentityMap $identityMap
     */
    private $identityMap;
    /**
     * @var IdentityRepositoryInterface
     */
    private $repository;
    /**
     * @var Transaction $transaction
     */
    private $transaction;

    /**
     * IdentityManager constructor.
     * @param IdentityMap $identityMap
     * @param IdentityRepositoryInterface $repository
     * @param RegisterNewUser $transaction
     */
    public function __construct(IdentityMap $identityMap, IdentityRepositoryInterface $repository, RegisterNewUser $transaction)
    {
        $this->repository = $repository;
        $this->identityMap = $identityMap;
        $this->transaction = $transaction;
    }

    /**
     * @param string $email
     * @param string $username
     * @param string $password
     * @throws IdentifierExistsException
     * @throws ValidationError
     * @throws \Phypes\Exception\EmptyRequiredValue
     * @throws \Phypes\Exception\InvalidRule
     * @throws \Exception
     */
    public function registerIdentity(string $email, string $username, string $password)
    {
        $this->identity = $this->identityMap->createStandardIdentity($email, $username, $password);
        $this->nonce = $this->identityMap->createNonceIdentity($this->getExpiryTime());
    }

    public function registerProfile(string $name, string $locale, $date)
    {

    }

    /**
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    private function getExpiryTime(): \DateTimeImmutable
    {
        $now = new \DateTimeImmutable("now");
        // Add two hours
        return $now->add(new \DateInterval("PT2H"));
    }

    public function save()
    {
        $this->transaction->registerIdentity($this->identity, $this->nonce);
        // Register profile
        $this->transaction->commit();
    }
}