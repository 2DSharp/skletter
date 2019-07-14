<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Mapper\Identity;


use Skletter\Model\Entity\StandardIdentity;

class StandardIdentityMapper
{
    /**
     * @var \PDO $connection
     */
    private $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetch(StandardIdentity $identity)
    {
        if ($identity->getId()) {
            $this->fetchById($identity);
            return;
        }
        $this->fetchByIdentifier($identity);

    }

    private function fetchById(StandardIdentity $identity)
    {

    }

    private function fetchByIdentifier(StandardIdentity $identity)
    {
        if ($identity->getType() == StandardIdentity::EMAIL) {
            $this->fetchByEmail($identity);
        } else {
            $this->fetchByUsername($identity);
        }
    }

    private function fetchByUsername(StandardIdentity $identity)
    {

    }

    private function fetchByEmail(StandardIdentity $identity)
    {
    }


}