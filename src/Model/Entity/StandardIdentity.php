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
use Phypes\Type\Password;
use Phypes\Type\StringRequired;
use Phypes\Type\Type;
use Phypes\Type\Username;
use Skletter\Contract\Entity\Identity;

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
    /**
     * @var Type $email
     */
    private $email;
    /**
     * @var Type $username
     */
    private $username;
    /**
     * @var string $status
     */
    private $status;

    public const EMAIL = 1;
    public const USERNAME = 2;
    private $found = false;

    /**
     * Initialize from an identifier.
     * @param string $identifier
     * @throws InvalidValue
     * @throws \Phypes\Exception\InvalidRule
     * @throws \Phypes\Exception\EmptyRequiredValue
     */
    public function setIdentifier(string $identifier)
    {
        try {
            $this->initializeFromEmail($identifier);
        } catch (InvalidValue $exception) {
            try {
                $this->initializeFromUsername($identifier);
            } catch (InvalidValue $exception) {
                throw new InvalidValue('Invalid username or email');
            }
        }
    }

    /**
     * @param string $email
     * @throws InvalidValue
     * @throws \Phypes\Exception\EmptyRequiredValue
     */
    private function initializeFromEmail(string $email)
    {
        $this->type = self::EMAIL;
        $this->identifier = new Email((new StringRequired(strtolower($email)))->getValue());
    }

    /**
     * @param $username
     * @throws InvalidValue
     * @throws \Phypes\Exception\InvalidRule
     * @throws \Phypes\Exception\EmptyRequiredValue
     */
    private function initializeFromUsername(string $username): void
    {
        $this->type = self::USERNAME;
        $this->identifier = new Username((new StringRequired(strtolower($username)))->getValue());
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

    public function setPassword(Password $password, int $hashCost = 12): void
    {
        $this->hashedPassword = password_hash($password->getValue(), PASSWORD_DEFAULT, ['cost' => $hashCost]);
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

    /**
     * @return Type
     */
    public function getEmail(): Type
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @throws InvalidValue
     */
    public function setEmail(string $email): void
    {
        $this->email = new Email($email);
    }

    /**
     * @return Type
     */
    public function getUsername(): Type
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @throws InvalidValue
     * @throws \Phypes\Exception\InvalidRule
     */
    public function setUsername(string $username): void
    {
        $this->username = new Username($username);
    }

    /**
     * @param Email $email
     */
    public function setTypedEmail(Email $email): void
    {
        $this->email = $email;
    }

    /**
     * @param Username $username
     */
    public function setTypedUsername(Username $username): void
    {
        $this->username = $username;
    }
    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}