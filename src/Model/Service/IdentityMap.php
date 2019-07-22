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


use Phypes\Error\Error;
use Phypes\Error\TypeErrorCode;
use Phypes\Exception\EmptyRequiredValue;
use Phypes\Exception\InvalidRule;
use Phypes\Exception\InvalidValue;
use Phypes\Type\Email;
use Phypes\Type\Password;
use Phypes\Type\StringRequired;
use Phypes\Type\Username;
use Skletter\Component\UserFriendlyError;
use Skletter\Contract\Entity\Identity;
use Skletter\Contract\Factory\QueryObjectFactoryInterface;
use Skletter\Contract\Repository\IdentityRepositoryInterface;
use Skletter\Exception\Domain\UserDoesNotExistException;
use Skletter\Exception\Domain\ValidationError;
use Skletter\Exception\IdentifierExistsException;
use Skletter\Exception\InvalidIdentifier;
use Skletter\Model\Entity\NonceIdentity;
use Skletter\Model\Entity\StandardIdentity;
use Skletter\Model\Query\FindIdentityByEmail;
use Skletter\Model\Query\FindIdentityByUsername;

/**
 * Class IdentityMap
 * @package Skletter\Model\Service
 */
class IdentityMap
{
    /**
     * @var QueryObjectFactoryInterface $queryFactory
     */
    private $factory;
    /**
     * @var IdentityRepositoryInterface $repository
     */
    private $repository;

    /**
     * IdentityLookup constructor.
     * @param IdentityRepositoryInterface $repository
     */
    /**
     * Map of error codes to error messages
     * @var array $errorMap
     */
    private $errorMap = [
        TypeErrorCode::EMAIL_INVALID => UserFriendlyError::INVALID_EMAIL_HELPFUL,
        TypeErrorCode::USERNAME_INVALID => UserFriendlyError::INVALID_USERNAME_HELPFUL,
        TypeErrorCode::PASSWORD_INVALID => UserFriendlyError::INVALID_PASSWORD_HELPFUL,
    ];

    public function __construct(IdentityRepositoryInterface $repository, QueryObjectFactoryInterface $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param StandardIdentity $identity
     * @throws UserDoesNotExistException
     */
    private function populateStandardIdentity(StandardIdentity $identity)
    {
        $this->repository->load($identity);
    }

    /**
     * Find and create a standard identity based on an identifier
     * @param $identifier
     * @return Identity
     * @throws InvalidIdentifier
     * @throws UserDoesNotExistException
     * @throws EmptyRequiredValue
     * @throws InvalidRule
     */
    public function getStandardIdentity($identifier): Identity
    {
        try {
            $identity = new StandardIdentity();
            $identity->setIdentifier($identifier);
            $this->populateStandardIdentity($identity);

            return $identity;
        } catch (InvalidValue $e) {
            throw new InvalidIdentifier();
        }
    }

    /**
     * Generates a one time use NonceIdentity
     * @param \DateTimeImmutable $validTill
     * @param int $tokenLength
     * @return NonceIdentity
     * @throws \Exception
     */
    public function createNonceIdentity(\DateTimeImmutable $validTill, int $tokenLength = 20): NonceIdentity
    {
        return new NonceIdentity($validTill, $tokenLength);
    }

    /**
     * Create a new standard identity, check for uniqueness on identifiers
     * @param string $email
     * @param string $username
     * @param string $password
     * @return StandardIdentity
     * @throws IdentifierExistsException
     * @throws ValidationError
     * @throws \Phypes\Exception\InvalidRule
     */
    public function createStandardIdentity(string $email, string $username, string $password): StandardIdentity
    {
        try {
            $identity = new StandardIdentity();
            $identity->setTypedEmail(new Email(new StringRequired($email)));
            $identity->setTypedUsername(new Username(new StringRequired($username)));
            $identity->setPassword(new Password(new StringRequired($password)));
            $identity->setStatus('Temp');
            $this->checkRedundancies($identity);

            return $identity;
        } catch (InvalidValue $exception) {
            /** @var Error $error */
            $error = $exception->getErrors()[0];
            throw new ValidationError(UserFriendlyError::getError($this->errorMap[$error->getCode()]));
        } catch (EmptyRequiredValue $exception) {
            throw new ValidationError(UserFriendlyError::getError(UserFriendlyError::EMPTY_REQUIRED));
        }
    }

    /**
     * Check DB records for already registered identifiers
     * @param StandardIdentity $identity
     * @throws IdentifierExistsException if identifier is already registered
     */
    private function checkRedundancies(StandardIdentity $identity): void
    {
        $query = $this->factory->create(FindIdentityByEmail::class);

        if ($query->find($identity))
            throw new IdentifierExistsException(UserFriendlyError::getError(UserFriendlyError::EMAIL_ALREADY_REGISTERED));

        $query = $this->factory->create(FindIdentityByUsername::class);

        if ($query->find($identity))
            throw new IdentifierExistsException(UserFriendlyError::getError(UserFriendlyError::USERNAME_ALREADY_REGISTERED));
    }
}