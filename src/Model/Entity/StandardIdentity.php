<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Entity;


use Phypes\Exception\InvalidValue;
use Phypes\Type\Email;
use Phypes\Type\Type;
use Phypes\Type\Username;
use Skletter\Contract\Identity;

class StandardIdentity implements Identity
{

    /**
     * Type of the identifier
     * @var int $type
     */
    private $type;
    /**
     * @var Type $identifier
     */
    private $identifier;
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $hashedPassword
     */
    private $hashedPassword;

    public const EMAIL = 1;
    public const USERNAME = 2;
    private $found = false;

    /**
     * Identity constructor.
     * @param string $identifier
     * @throws InvalidValue
     * @throws \Phypes\Exception\InvalidRule
     */
    public function __construct(string $identifier)
    {
        try {
            $this->createIdentityFromEmail($identifier);
        } catch (InvalidValue $exception) {
            try {
                $this->createIdentityFromUsername($identifier);
            } catch (InvalidValue $exception) {
                throw new InvalidValue('Invalid username or email');
            }
        }
    }

    /**
     * @param string $email
     * @throws InvalidValue
     */
    private function createIdentityFromEmail(string $email)
    {
        $this->identifier = new Email(strtolower($email));
        $this->type = self::EMAIL;
    }

    /**
     * @param $username
     * @throws InvalidValue
     * @throws \Phypes\Exception\InvalidRule
     */
    private function createIdentityFromUsername($username): void
    {
        $this->identifier = new Username(strtolower($username));
        $this->type = self::USERNAME;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return Type
     */
    public function getIdentifier(): Type
    {
        return $this->identifier;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Is the account found?
     * @return bool
     */
    public function isFound(): bool
    {
        return $this->found;
    }
    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->found = true;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    /**
     * @param string $hashedPassword
     */
    public function setHashedPassword(string $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
    }
}