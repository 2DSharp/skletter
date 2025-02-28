<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Factory;


use PDO;
use RuntimeException;
use Skletter\Contract\Factory\MapperFactoryInterface;

class MapperFactory implements MapperFactoryInterface
{
    private $connection;
    private $cache = [];

    /**
     * Creates new factory instance
     *
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Retrieve SQL data mapper instance for repositories
     *
     * @param string $className Fully qualified class name of the mapper
     *
     * @throws RuntimeException if mapper's class can't be found
     *
     * @return object
     */
    public function create(string $className): object
    {
        if (array_key_exists($className, $this->cache)) {
            return $this->cache[$className];
        }

        $this->performChecks($className);

        $instance = new $className($this->connection);
        $this->cache[$className] = $instance;
        return $instance;
    }

    /**
     * @param string $className
     */
    private function performChecks(string $className)
    {

        if (!class_exists($className)) {
            throw new RuntimeException("Mapper not found. Attempted to load '{$className}'.");
        }
    }
}