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
use Skletter\Contract\Entity\Identity;
use Skletter\Exception\InvalidCookie;

class CookieIdentity implements Identity
{
    /**
     * IdentityID
     *
     * @var int $id
     */
    private $id;
    /**
     * @var string $token
     */
    private $token;
    /**
     * @var \DateTimeImmutable $validTill
     */
    private $validTill;

    /**
     * CookieIdentity constructor.
     *
     * @param  string $token
     * @throws InvalidCookie
     */
    public function __construct(string $token)
    {
        if (SecureTokenManager::isTampered($token)) {
            throw new InvalidCookie('The cookie is tampered. Possible attack.');
        }
        $this->token = $token;
    }


    public function getIdentifier()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    function setId(int $id): void
    {
        $this->id = $id;
    }

    function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getValidTill(): \DateTimeImmutable
    {
        return $this->validTill;
    }

    /**
     * @param \DateTimeImmutable $validTill
     */
    public function setValidTill(\DateTimeImmutable $validTill): void
    {
        $this->validTill = $validTill;
    }

}