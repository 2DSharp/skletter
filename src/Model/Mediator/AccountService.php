<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Mediator;

use Skletter\Model\RemoteService\Authentication\AuthenticationClient;
use Skletter\Model\RemoteService\DTO\UserDTO;
use Skletter\Model\RemoteService\Exception\DTONullException;
use Skletter\Model\RemoteService\Exception\NonExistentUser;
use Skletter\Model\RemoteService\Exception\NullDTOException;
use Skletter\Model\RemoteService\Exception\PasswordMismatch;
use Skletter\Model\RemoteService\Exception\UserExists;
use Skletter\Model\RemoteService\Exception\ValidationError;
use Skletter\Model\RemoteService\UserService\UserServiceClient;
use Skletter\Model\ValueObject\RegistrationResult;

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
     */
    public function __construct(UserServiceClient $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Create a new user account
     *
     * @param array $data
     * @return RegistrationResult
     */
    public function register(array $data)
    {
        try {
            $user = new UserDTO();
            $user->name = $data['name'];
            $user->username = $data['username'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->ipAddr = $data['ipAddr'];

            $this->userService->registerNew($user);

            return new RegistrationResult(true);
        } catch (ValidationError $e) {
            return new RegistrationResult(false, $e->errors);
        } catch (UserExists $e) {
            return new RegistrationResult(false, [$e->field => $e->error]);
        } catch (NullDTOException $e) {
            return new RegistrationResult(false, ["global" => "Something went wrong. Try again."]);
        }
    }

    public function loginWithPassword(string $identifier, string $password): UserDTO
    {
        try {
            return $this->userService->loginWithPassword($identifier, $password);
        } catch (NonExistentUser | PasswordMismatch $e) {

        }
    }
}