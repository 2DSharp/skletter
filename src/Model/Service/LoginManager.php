<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Service;


use Phypes\Exception\InvalidValue;
use Phypes\Type\Password;
use Skletter\Contract\Identity;
use Skletter\Exception\InvalidPassword;
use Skletter\Model\Entity\StandardIdentity;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginManager
{
    /**
     * Logged in status
     * @var bool $isLoggedIn
     */
    private $isLoggedIn = false;
    /**
     * @var Session $session
     */
    private $session;
    /**
     * @var array|string $errors
     */
    private $errors = [];

    /**
     * LoginManager constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param StandardIdentity $identity
     * @param string $rawPassword
     * @throws PasswordMismatch
     * @throws InvalidPassword
     * @throws \Phypes\Exception\InvalidRule
     */
    public function loginWithPassword(StandardIdentity $identity, string $rawPassword)
    {
        try {
            $password = new Password($rawPassword);
            if (password_verify($password->getValue(), $identity->getHashedPassword()))
                $this->login($identity);
            else
                throw new PasswordMismatch('The password you entered is invalid');

        } catch (InvalidValue $e) {
            throw new InvalidPassword('The password you entered is invalid');
        }
    }

    /**
     * Sets the login state, by updating db and logging data
     * @param Identity $identity
     */
    public function login(Identity $identity)
    {
        $this->isLoggedIn = true;
        $this->session->set('UserID', $identity->getID());
        // Log stuff here
    }

    /**
     * Push errors to be accessed by view
     * @param string $error
     */
    public function appendError(string $error): void
    {
        array_push($this->errors, $error);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFirstError()
    {
        return $this->errors[0];
    }

    public function isLoggedIn(): bool
    {
        return $this->isLoggedIn;
    }

}