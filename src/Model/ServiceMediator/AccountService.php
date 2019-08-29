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
use Skletter\Model\RemoteService\Entity\Profile;
use Skletter\Model\RemoteService\Entity\User;
use Skletter\Model\RemoteService\UserService\UserServiceClient;

/**
 * A mediator between the Remote services to enable registration
 * Class RegistrationService
 * @package Skletter\Model\Service
 */
class AccountService
{
    /**
     * Remote user management service
     * @var UserServiceClient $userService
     */
    private $userService;
    /**
     * Remote authentication service
     * @var AuthenticationClient $auth
     */
    private $auth;

    /**
     * AccountService constructor.
     * @param UserServiceClient $userService
     * @param AuthenticationClient $auth
     */
    public function __construct(UserServiceClient $userService, AuthenticationClient $auth)
    {
        $this->userService = $userService;
        $this->auth = $auth;
    }

    /**
     * Create a new user account
     *
     * @param array $data
     * @throws \Skletter\Model\RemoteService\Exception\NonExistentUser
     * @throws \Skletter\Model\RemoteService\Exception\UserExists
     * @throws \Skletter\Model\RemoteService\Exception\ValidationError
     */
    public function register(array $data)
    {
        $user = new User(['email' => $data['email'], 'username' => $data['username']]);
        $profile = new Profile(['username' => $data['username'], 'name' => $data['name']]);

        $this->userService->registerNew($user, $profile);
        $user = $this->userService->getUserByEmail($user->getEmail());

        $this->auth->createStandardIdentity($user->getId(), $data['password']);
    }
}