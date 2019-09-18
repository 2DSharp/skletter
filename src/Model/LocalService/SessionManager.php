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

use Skletter\Model\RemoteService\DTO\UserDTO;
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
    private $persistence;

    public function __construct(SessionInterface $session)
    {
        $this->persistence = $session;
    }

    public function storeLoginDetails(UserDTO $dto): void
    {
        $this->persistence->set("name", $dto->name);
        $this->persistence->set("email", $dto->email);
        $this->persistence->set("username", $dto->username);
        $this->persistence->set("id", $dto->id);
    }

    public function getLoginDetails(): UserDTO
    {
        $dto = new UserDTO();

        $dto->name = $this->persistence->get("name");
        $dto->email = $this->persistence->get("email");
        $dto->username = $this->persistence->get("username");
        $dto->id = $this->persistence->get("id");
    }
}