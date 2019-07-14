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


use Phypes\Exception\InvalidValue;
use Skletter\Contract\Entity\Identity;
use Skletter\Contract\Factory\QueryObjectFactoryInterface;
use Skletter\Contract\Query\QueryObject;
use Skletter\Exception\InvalidIdentifier;
use Skletter\Exception\UserDoesntExistException;
use Skletter\Model\Entity\StandardIdentity;
use Skletter\Model\Query\LoadIdentityByEmail;
use Skletter\Model\Query\LoadIdentityByUsername;

class IdentityLookup
{
    /**
     * @var QueryObjectFactoryInterface $queryFactory
     */
    private $queryFactory;


    public function __construct(QueryObjectFactoryInterface $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    /**
     * @param StandardIdentity $identity
     * @throws UserDoesntExistException
     */
    private function populateStandardIdentity(StandardIdentity $identity)
    {
        /**
         * @var QueryObject $queryObject
         */
        if ($identity->getType() == StandardIdentity::EMAIL) {
            $queryObject = $this->queryFactory->create(LoadIdentityByEmail::class);
        } else {
            $queryObject = $this->queryFactory->create(LoadIdentityByUsername::class);
        }

        $queryObject->execute($identity);

        if (!$identity->isFound())
            throw new UserDoesntExistException();
    }

    /**
     * @param $identifier
     * @return Identity
     * @throws InvalidIdentifier
     * @throws UserDoesntExistException
     * @throws \Phypes\Exception\EmptyRequiredValue
     * @throws \Phypes\Exception\InvalidRule
     */
    public function getStandardIdentity($identifier): Identity
    {
        try {
            $identity = new StandardIdentity($identifier);
            $this->populateStandardIdentity($identity);
            return $identity;
        } catch (InvalidValue $e) {
            throw new InvalidIdentifier();
        }
    }

}