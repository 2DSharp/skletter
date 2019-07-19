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


use Skletter\Contract\Entity\Identity;

class Profile
{
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var \DateTimeImmutable $birthday
     */
    private $birthday;
    /**
     * @var string $locale
     */
    private $locale;
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var Identity $identity
     */
    private $identity;

    public function __construct(Identity $identity)
    {
        $this->identity = $identity;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getBirthday(): \DateTimeImmutable
    {
        return $this->birthday;
    }

    /**
     * @param \DateTimeImmutable $birthday
     */
    public function setBirthday(\DateTimeImmutable $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Identity
     */
    public function getIdentity(): Identity
    {
        return $this->identity;
    }

    /**
     * @param Identity $identity
     */
    public function setIdentity(Identity $identity): void
    {
        $this->identity = $identity;
    }

}