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
use Skletter\Contract\Factory\QueryObjectFactoryInterface;
use Skletter\Contract\Repository\IdentityRepositoryInterface;
use Skletter\Exception\InvalidIdentifier;
use Skletter\Exception\UserDoesNotExistException;
use Skletter\Model\Entity\StandardIdentity;


class IdentityLookup
{
    /**
     * @var QueryObjectFactoryInterface $queryFactory
     */
    private $queryFactory;
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
     * @param StandardIdentity $identity
     * @throws UserDoesNotExistException
     */
    private function populateStandardIdentity(StandardIdentity $identity)
    {
        $this->repository->load($identity);

        if (!$identity->isFound())
            throw new UserDoesNotExistException();
    }

    /**
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

}