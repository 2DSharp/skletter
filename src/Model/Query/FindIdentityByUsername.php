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

class FindIdentityByUsername extends StandardIdentityLoader implements QueryObject
{
    private $query = /**
     * @lang MySQL
     */
        "SELECT 1 FROM Identity WHERE Username = :username";

    /**
     * Does the username in the entity already exist in the db?
     *
     * @param  StandardIdentity $identity
     * @return bool
     */
    public function find($identity): bool
    {
        return $this->exists($this->query, ':username', $identity->getUsername());
    }
}