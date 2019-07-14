<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Query;


use Skletter\Contract\Query\QueryObject;
use Skletter\Model\Entity\StandardIdentity;

class LoadIdentityByUsername implements QueryObject
{
    private $query = /** @lang MySQL */
        "SELECT ID, HashedPassword FROM Identity WHERE Username = :username";

    /**
     * @param StandardIdentity $entity
     */
    public function execute($entity)
    {
        $this->executeQuery($entity, $this->query, ':username');

    }
}