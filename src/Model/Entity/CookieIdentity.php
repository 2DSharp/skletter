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

use Skletter\Component\SecureTokenManager;
use Skletter\Component\ValueObject\TokenKeyPair;
use Skletter\Contract\Entity\Identity;
use Skletter\Exception\InvalidCookie;

class CookieIdentity implements Identity
{
    /**
     * @var string $hmacKey
     */
    private $hmacKey;
    private $id;
    /**
     * @var string $token
     */
    private $token;

    public static function createNew()
    {
        $pair = SecureTokenManager::generate();

        $identity = new CookieIdentity();
        $identity->setToken($pair->getToken());
        $identity->setHmacKey($pair->getKey());
        return $identity;
    }

    /**
     * @param TokenKeyPair $pair
     * @throws InvalidCookie
     */
    public function createFromTokenKeyPair(TokenKeyPair $pair)
    {
        if (SecureTokenManager::isTampered($pair)) {
            throw new InvalidCookie();
        }

        $this->setHmacKey($pair->getKey());
        $this->setToken($pair->getToken());
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifier()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getHmacKey(): string
    {
        return $this->hmacKey;
    }

    /**
     * @param string $hmacKey
     */
    public function setHmacKey(string $hmacKey): void
    {
        $this->hmacKey = $hmacKey;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}