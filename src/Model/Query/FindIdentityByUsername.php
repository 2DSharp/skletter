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


use Skletter\Model\Entity\StandardIdentity;

class FindIdentityByUsername extends StandardIdentityLoader
{
    private $query = /** @lang MySQL */
        "SELECT 1 FROM Identity WHERE Username = :username";

    /**
     * Does the username in the entity already exist in the db?
     * @param StandardIdentity $identity
     * @return bool
     */
    public function execute($identity): bool
    {
        return $this->find($identity, $this->query, ':username');
    }
}