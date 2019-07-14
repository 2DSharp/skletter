<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\DTO;


use Skletter\Contract\Entity\Identity;

class LoginState
{
    /**
     * User Identity
     * @var Identity $identity
     */
    private $identity;
    /**
     * Login errors
     * @var string $error
     */
    private $error;
    /**
     * LoggedIn status
     * @var bool $isLoggedIn
     */
    private $isLoggedIn = false;

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
        $this->setIsLoggedIn(true);
        $this->identity = $identity;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->isLoggedIn;
    }

    /**
     * @param bool $isLoggedIn
     */
    private function setIsLoggedIn(bool $isLoggedIn): void
    {
        $this->isLoggedIn = $isLoggedIn;
    }


}