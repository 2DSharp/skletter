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
use Skletter\Contract\Identity;
use Skletter\Exception\InvalidIdentifier;
use Skletter\Exception\UserDoesntExistException;
use Skletter\Model\Entity\StandardIdentity;
use Skletter\Model\Mapper\IdentityMapper;

class IdentityLookup
{
    public function __construct(IdentityMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param StandardIdentity $identity
     * @throws UserDoesntExistException
     */
    private function populateStandardIdentity(StandardIdentity $identity)
    {
        if ($identity->getType() == StandardIdentity::EMAIL) {
            $this->mapper->findByEmail($identity);
        } else {
            $this->mapper->findByUsername($identity);
        }

        if (!$identity->isFound())
            throw new UserDoesntExistException();
    }

    /**
     * @param $identifier
     * @return Identity
     * @throws \Phypes\Exception\InvalidRule
     * @throws UserDoesntExistException
     * @throws InvalidIdentifier
     * @throws \Phypes\Exception\EmptyRequiredValue
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