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
use Skletter\Contract\Mapper\DataMapper;
use Skletter\Contract\Repository\IdentityRepositoryInterface;
use Skletter\Exception\InvalidDataMapper;
use Skletter\Exception\MapperNotSpecified;
use Skletter\Factory\MapperFactory;
use Skletter\Model\Entity;
use Skletter\Model\Mapper;

class IdentityRepository implements IdentityRepositoryInterface
{
    /**
     * @var MapperFactory $factory
     */
    private $factory;

    private $table = [
        Entity\StandardIdentity::class => Mapper\Identity\StandardIdentityMapper::class
    ];

    /**
     * IdentityRepository constructor.
     * @param MapperFactoryInterface $factory
     */
    public function __construct(MapperFactoryInterface $factory)
    {
        $this->factory = $factory;
    }


    /**
     * @param Identity $identity
     * @throws InvalidDataMapper
     * @throws MapperNotSpecified
     */
    public function save(Identity $identity)
    {
        /**
         * @var DataMapper $mapper
         */
        $mapper = $this->buildMapperFromIdentity($identity);
        $mapper->store($identity);
    }

    /**
     * @param Identity $identity
     * @throws InvalidDataMapper
     * @throws MapperNotSpecified
     */
    public function delete(Identity $identity)
    {
        /**
         * @var DataMapper $mapper
         */
        $mapper = $this->buildMapperFromIdentity($identity);
        $mapper->delete($identity);
    }

    public function has(Identity $identity)
    {
        // TODO: Implement has() method.
    }


    /**
     * @param Identity $identity
     * @throws InvalidDataMapper
     * @throws MapperNotSpecified
     */
    function update(Identity $identity)
    {
        /**
         * @var DataMapper $mapper
         */
        $mapper = $this->buildMapperFromIdentity($identity);
        $mapper->update($identity);
    }

    /**
     * @param Identity $identity
     * @return DataMapper
     * @throws MapperNotSpecified
     * @throws InvalidDataMapper
     */
    public function buildMapperFromIdentity(Identity $identity): DataMapper
    {
        $key = get_class($identity);

        if (!array_key_exists($key, $this->table)) {
            throw new MapperNotSpecified("No mapper has been specified for the entity '{$key}'");
        }

        return $this->factory->create($this->table[$key]);
    }

}