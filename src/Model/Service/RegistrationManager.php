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


use Skletter\Contract\Transaction;
use Skletter\Exception\IdentifierExistsException;
use Skletter\Exception\PDOExceptionWrapper\UniqueConstraintViolation;
use Skletter\Exception\RegistrationFailure;
use Skletter\Exception\ValidationError;
use Skletter\Model\Entity\NonceIdentity;
use Skletter\Model\Entity\Profile;
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
     * @var Profile $profile
     */
    private $profile;
    /**
     * Mapping service for identity look ups
     * @var IdentityMap $identityMap
     */
    private $identityMap;
    /**
     * @var Transaction $transaction
     */
    private $transaction;

    /**
     * IdentityManager constructor.
     * @param IdentityMap $identityMap
     * @param RegisterNewUser $transaction
     */
    public function __construct(IdentityMap $identityMap, RegisterNewUser $transaction)
    {
        $this->identityMap = $identityMap;
        $this->transaction = $transaction;
    }

    /**
     * @param string $email
     * @param string $username
     * @param string $password
     * @return void
     * @throws IdentifierExistsException
     * @throws ValidationError
     * @throws \Phypes\Exception\EmptyRequiredValue
     * @throws \Phypes\Exception\InvalidRule
     * @throws \Exception
     */
    public function registerIdentity(string $email, string $username, string $password)
    {
        $this->identity = $this->identityMap->createStandardIdentity($email, $username, $password);
        $this->nonce = $this->identityMap->createNonceIdentity($this->getExpiryTime(), 32);
    }

    public function getStandardIdentity(): StandardIdentity
    {
        return $this->identity;
    }

    public function getNonceIdentity(): NonceIdentity
    {
        return $this->nonce;
    }

    public function registerProfile(string $name, string $locale, $date)
    {
        $this->profile = new Profile($this->identity);
        $this->profile->setName($name);
        $this->profile->setLocale($locale);
        $this->profile->setBirthday($date);
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

    /**
     * @return bool
     * @throws RegistrationFailure
     */
    public function save()
    {
        try {
            $this->transaction->registerIdentity($this->identity, $this->nonce);
            $this->transaction->registerProfile($this->profile);
            return $this->transaction->commit();
        } catch (UniqueConstraintViolation $e) {
            throw new RegistrationFailure("The " . strtolower($e->getOffendingField()) . " you have provided is already in use. 
            Perhaps you'd want to log in instead?");
        }

    }
}