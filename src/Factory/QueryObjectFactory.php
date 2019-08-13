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
use Skletter\Contract\Factory\QueryObjectFactoryInterface;
use Skletter\Contract\Query\QueryObject;
use Skletter\Exception\InvalidQueryObject;

class QueryObjectFactory implements QueryObjectFactoryInterface
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
     * Retrieve Query Object instance for read models
     *
     * @param string $className Fully qualified class name of the QueryObject
     *
     * @throws RuntimeException if mapper's class can't be found
     * @throws InvalidQueryObject if mapper doesn't implement DataMapper interface
     *
     * @return QueryObject
     */
    public function create(string $className): QueryObject
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
     * @param  string $className
     * @throws InvalidQueryObject
     */
    private function performChecks(string $className)
    {

        if (!class_exists($className)) {
            throw new RuntimeException("QueryObject not found. Attempted to load '{$className}'.");
        }
        if (!is_a($className, QueryObject::class, true)) {
            throw new InvalidQueryObject("The class '{$className}' does not implement the QueryObject interface");
        }
    }
}