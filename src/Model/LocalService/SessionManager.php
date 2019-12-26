<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\LocalService;

use Skletter\Model\Entity\CurrentUser;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SessionManager is responsible for abstracting out the session persistence logic
 * This maps between domain use-cases like login and localstorage/cookies
 * Provides an uniform way to access session vars
 *
 * @package Skletter\Model\LocalService
 */
class SessionManager
{
    private SessionInterface $persistence;

    public function __construct(SessionInterface $session)
    {
        $this->persistence = $session;
    }

    public function getId(): string
    {
        return $this->persistence->getId();
    }

    public function storeLoginDetails(CurrentUser $user): void
    {
        $this->persistence->set("name", $user->getName());
        $this->persistence->set("email", $user->getEmail());
        $this->persistence->set("username", $user->getUsername());
        $this->persistence->set("id", $user->getId());
        $this->persistence->set("status", $user->getStatus());
    }

    public function getLoginDetails(): CurrentUser
    {
        return new CurrentUser($this->persistence->get("id"),
                               $this->persistence->get("name"),
                               $this->persistence->get("username"),
                               $this->persistence->get("email"),
                               $this->persistence->get("status")
        );
    }

    public function isLoggedIn(): bool
    {
        return null !== ($this->persistence->get('id'));
    }
}