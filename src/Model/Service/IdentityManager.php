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
use Phypes\Exception\InvalidValue;
use Phypes\Type\Email;
use Phypes\Type\Password;
use Phypes\Type\StringRequired;
use Phypes\Type\Username;
use Skletter\Contract\Factory\QueryObjectFactoryInterface;
use Skletter\Contract\Repository\IdentityRepositoryInterface;
use Skletter\Exception\IdentifierExistsException;
use Skletter\Exception\ValidationError;
use Skletter\Model\Entity\StandardIdentity;
use Skletter\Model\Query\FindIdentityByEmail;
use Skletter\Model\Query\FindIdentityByUsername;

/**
 * Class IdentityManager
 *
 * Responsible for managing identities including - Creation, Manipulation and Deletion of Identities.
 * @package Skletter\Model\Service
 */
class IdentityManager
{
    /**
     * Factory to build query objects
     * @var QueryObjectFactoryInterface $factory
     */
    private $factory;
    /**
     * Repository to manipulate Identity instances
     * @var IdentityRepositoryInterface $repository
     */
    private $repository;

    private $errorMap = [
        TypeErrorCode::EMAIL_INVALID => "The email you entered is invalid. Please check your email and try again",
        TypeErrorCode::USERNAME_INVALID => "The username you entered isn't correct. 2-14 characters including alphabets, 
        digits and underscores (_) are allowed"
    ];

    /**
     * IdentityManager constructor.
     * @param IdentityRepositoryInterface $repository
     * @param QueryObjectFactoryInterface $factory
     */
    public function __construct(IdentityRepositoryInterface $repository, QueryObjectFactoryInterface $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param string $email
     * @param string $username
     * @param string $password
     * @return StandardIdentity
     * @throws IdentifierExistsException
     * @throws ValidationError
     * @throws \Phypes\Exception\EmptyRequiredValue
     * @throws \Phypes\Exception\InvalidRule
     */
    public function createStandardIdentity(string $email, string $username, string $password): StandardIdentity
    {
        try {
            $identity = new StandardIdentity();
            $identity->setTypedEmail(new Email(new StringRequired($email)));
            $identity->setTypedUsername(new Username(new StringRequired($username)));
            $identity->setPassword(new Password(new StringRequired($password)));

            $this->checkRedundancies($identity);

            return $identity;
        } catch (InvalidValue $exception) {
            /**
             * @var Error $error
             */
            $error = $exception->getErrors()[0];
            throw new ValidationError($this->errorMap[$error->getCode()]);
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

        if (!$query->find($identity))
            throw new IdentifierExistsException("An account with the email you entered already exists." .
                "Perhaps you'd want to log in instead?");


        $query = $this->factory->create(FindIdentityByUsername::class);

        if (!$query->find($identity))
            throw new IdentifierExistsException("An account with the username you entered already exists." .
                "Perhaps you'd want to log in instead?");
    }
}