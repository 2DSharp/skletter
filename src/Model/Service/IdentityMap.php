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

use Phypes\Exception\EmptyRequiredValue;
use Phypes\Exception\InvalidRule;
use Phypes\Exception\InvalidValue;
use Skletter\Contract\Entity\Identity;
use Skletter\Contract\Repository\IdentityRepositoryInterface;
use Skletter\Exception\Domain\UserDoesNotExistException;
use Skletter\Exception\InvalidIdentifier;
use Skletter\Model\Entity\CookieIdentity;
use Skletter\Model\Entity\StandardIdentity;

/**
 * TODO: Turn it into a StandardIdentity query
 * Class IdentityMap
 * @package Skletter\Model\Service
 */
class IdentityMap
{
    /**
     * @var IdentityRepositoryInterface $repository
     */
    private $repository;

    /**
     * IdentityLookup constructor.
     * @param IdentityRepositoryInterface $repository
     */
    public function __construct(IdentityRepositoryInterface $repository)
    {
        $this->repository = $repository;
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
            $this->repository->load($identity);

            return $identity;
        } catch (InvalidValue $e) {
            throw new InvalidIdentifier();
        }
    }

    /**
     * @param string $token
     * @return CookieIdentity
     * @throws UserDoesNotExistException
     * @throws \Skletter\Exception\InvalidCookie
     */
    public function getCookieIdentity(string $token): CookieIdentity
    {
        $identity = new CookieIdentity($token);
        $this->repository->load($identity);
        return $identity;
    }
}