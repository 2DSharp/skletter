<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\ServiceMediator;


use Skletter\Model\RemoteService\Authentication\AuthenticationClient;
use Skletter\Model\RemoteService\Entity\User;
use Skletter\Model\RemoteService\UserService\UserServiceClient;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionManager
{
    /**
     * Remote authentication service
     * @var AuthenticationClient $auth
     */
    private $auth;
    /**
     * Remote user management service
     * @var UserServiceClient $userService
     */
    private $userService;
    /**
     * @var SessionInterface $session
     */
    private $session;

    /**
     * SessionManager constructor.
     * @param UserServiceClient $userService
     * @param AuthenticationClient $auth
     * @param SessionInterface $session
     */
    public function __construct(UserServiceClient $userService,
                                AuthenticationClient $auth,
                                SessionInterface $session)
    {
        $this->userService = $userService;
        $this->auth = $auth;
        $this->session = $session;
    }

    /**
     * Allow login through password and an identifier
     * @param string $identifier
     * @param string $password
     * @param string $userAgent
     * @throws \Skletter\Model\RemoteService\Exception\NonExistentUser
     */
    public function loginWithPassword(string $identifier, string $password, string $userAgent): void
    {
        $user = $this->userService->getUserByIdentifier($identifier);
        $this->auth->authenticatePassword($user->getId(), $password);
        $this->auth->createCookieIdentity($user->getId(), $userAgent);

        $this->setSessionData($user);
    }

    /**
     * Set the session data upon successful login
     * @param User $user
     * @throws \Skletter\Model\RemoteService\Exception\NonExistentUser
     */
    private function setSessionData(User $user): void
    {
        $profile = $this->userService->getProfile($user->getId());
        $this->session->set('name', $profile->getName());
        $this->session->set('id', $user->getId());
        $this->session->set('username', $user->getUsername());
        $this->session->set('picture', $profile->getPicture());
    }
}