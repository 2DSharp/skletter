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

class FindIdentityByEmail extends StandardIdentityLoader implements QueryObject
{
    private $query = /** @lang MySQL */
        "SELECT 1 FROM Identity WHERE Email = :email";

    /**
     * Does the email in the entity already exist in the db?
     * @param StandardIdentity $identity
     * @return bool
     */
    public function find($identity): bool
    {
        /**
         * @var \PDOStatement $stmt
         */
        return $this->exists($this->query, ':email', $identity->getEmail());
    }

}