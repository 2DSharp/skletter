<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Repository;


use Skletter\Contract\Entity\Identity;
use Skletter\Contract\Factory\MapperFactoryInterface;
use Skletter\Contract\Repository\IdentityRepositoryInterface;
use Skletter\Exception\Domain\UserDoesNotExistException;
use Skletter\Exception\InvalidDataMapper;
use Skletter\Exception\Mapper\RecordNotFound;
use Skletter\Exception\MapperNotSpecified;
use Skletter\Factory\MapperFactory;
use Skletter\Model\Entity;
use Skletter\Model\Mapper;
use Skletter\Model\Mapper\Identity\IdentityMapper;

class IdentityRepository implements IdentityRepositoryInterface
{
    /**
     * @var MapperFactory $factory
     */
    private $factory;

    private $table = [
        Entity\StandardIdentity::class => Mapper\Identity\StandardIdentityMapper::class,
        Entity\NonceIdentity::class => Mapper\Identity\NonceIdentityMapper::class,
        Entity\CookieIdentity::class => Mapper\Identity\CookieIdentityMapper::class
    ];

    /**
     * IdentityRepository constructor.
     *
     * @param MapperFactoryInterface $factory
     */
    public function __construct(MapperFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param  Identity $identity
     * @throws InvalidDataMapper
     * @throws MapperNotSpecified
     * @throws UserDoesNotExistException
     */
    public function load(Identity $identity)
    {
        try {
            /**
             * @var Entity\StandardIdentity $identity
             */
            $mapper = $this->buildMapperFromIdentity($identity);
            $mapper->fetch($identity, ['ID', 'Email', 'Username', 'Status', 'HashedPassword']);
        } catch (RecordNotFound $e) {
            throw new UserDoesNotExistException();
        }
    }

    /**
     * @param  Identity $identity
     * @throws InvalidDataMapper
     * @throws MapperNotSpecified
     */
    public function save(Identity $identity)
    {
        /**
         * @var IdentityMapper $mapper
         */
        $mapper = $this->buildMapperFromIdentity($identity);
        $mapper->store($identity);
    }

    /**
     * @param  Identity $identity
     * @throws InvalidDataMapper
     * @throws MapperNotSpecified
     */
    public function delete(Identity $identity)
    {
        /**
         * @var IdentityMapper $mapper
         */
        $mapper = $this->buildMapperFromIdentity($identity);
        $mapper->delete($identity);
    }
    /**
     * @param  Identity $identity
     * @throws InvalidDataMapper
     * @throws MapperNotSpecified
     */
    function update(Identity $identity)
    {
        /**
         * @var $mapper
         */
        $mapper = $this->buildMapperFromIdentity($identity);
        $mapper->update($identity);
    }

    /**
     * @param  Identity $identity
     * @return object|IdentityMapper
     * @throws MapperNotSpecified
     * @throws InvalidDataMapper
     */
    public function buildMapperFromIdentity(Identity $identity)
    {
        $key = get_class($identity);
        $mapper = $this->table[$key];
        if (!array_key_exists($key, $this->table)) {
            throw new MapperNotSpecified("No mapper has been specified for the entity '{$key}'");
        }

        if (!is_a($mapper, IdentityMapper::class, true)) {
            throw new InvalidDataMapper("The class '{$mapper}' does not implement the IdentityMapper abstract functions");
        }

        /**
         * @var IdentityMapper $mapper
         */
        return $this->factory->create($mapper);
    }

}