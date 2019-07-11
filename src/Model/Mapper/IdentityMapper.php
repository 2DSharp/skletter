<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Mapper;


use Skletter\Model\Entity\StandardIdentity;

class IdentityMapper
{

    public function findByEmail(StandardIdentity $identity)
    {
        $identity->setId(123);
    }

    public function findByUsername(StandardIdentity $identity)
    {
    }
}