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

/**
 * Class NonceIdentity
 * Allows to create one-time login identities for limited validity period.
 * Typically to be used for contact information confirmation or account recovery.
 * @package Skletter\Model\Entity
 */
class NonceIdentity implements Identity
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $token
     */
    private $token;
    /**
     * @var string $pin
     */
    private $pin;
    /**
     * @var \DateTimeImmutable $validTill
     */
    private $validTill;


    /**
     * NonceIdentity constructor.
     * @param \DateTimeImmutable $validTill
     * @param int $tokenLength
     * @throws \Exception
     */
    public function __construct(\DateTimeImmutable $validTill, int $tokenLength = 20)
    {
        $this->validTill = $validTill;
        $this->generateSecrets($tokenLength);
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

    /**
     * @return string
     */
    public function getPin(): string
    {
        return $this->pin;
    }

    /**
     * @param int $pin
     */
    public function setPin(string $pin): void
    {
        $this->pin = $pin;
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

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifier(): int
    {
        return $this->getId();
    }

    private function generatePin(): string
    {
        do {
            $num = sprintf('%06d', mt_rand(100, 999989));
        } while (preg_match("~^(\d)\\1\\1|(\d)\\2\\2$|000~", $num));

        return $num;
    }

    /**
     * @param $length
     * @return string
     * @throws \Exception
     */
    private function generateToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }

        return $token;
    }

    /**
     * @param int $tokenLength
     * @throws \Exception
     */
    private function generateSecrets(int $tokenLength)
    {
        $this->pin = $this->generatePin();
        $this->token = $this->generateToken($tokenLength);
    }
}