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


use Skletter\Model\Entity\Profile;

class ProfileMapper
{
    /**
     * @var \PDO $connection
     */
    private $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetch(Profile $identity, array $fields): void
    {

    }

    public function store(Profile $profile)
    {
        /**
         * @var Profile $profile
         */
        $command = /** @lang MySQL */
            "INSERT INTO Profile (Name, Birthday, Locale, IdentityID) VALUES (:name, :bday, :locale, :id)";
        $statement = $this->connection->prepare($command);

        $statement->bindValue(':name', $profile->getName());
        $statement->bindValue(':bday', $profile->getBirthday()->format('Y-m-d'));
        $statement->bindValue(':locale', $profile->getLocale());
        $statement->bindValue(':id', $profile->getIdentity()->getId());

        $statement->execute();

        $profile->setId($this->connection->lastInsertId());
    }


    public function update($entity): bool
    {
        // TODO: Implement update() method.
    }

    public function delete($entity): bool
    {
        // TODO: Implement delete() method.
    }
}