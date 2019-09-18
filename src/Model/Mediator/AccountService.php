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

use Skletter\Model\LocalService\SessionManager;
use Skletter\Model\RemoteService\DTO\UserDTO;
use Skletter\Model\RemoteService\Exception\DTONullException;
use Skletter\Model\RemoteService\Exception\NonExistentUser;
use Skletter\Model\RemoteService\Exception\NullDTOException;
use Skletter\Model\RemoteService\Exception\PasswordMismatch;
use Skletter\Model\RemoteService\Exception\UserExists;
use Skletter\Model\RemoteService\Exception\ValidationError;
use Skletter\Model\RemoteService\UserService\UserServiceClient;
use Skletter\Model\ValueObject\Result;

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
     * Local session management service
     * @var SessionManager $session
     */
    private $session;

    /**
     * AccountService constructor.
     * @param UserServiceClient $userService
     * @param SessionManager $session
     */
    public function __construct(UserServiceClient $userService, SessionManager $session)
    {
        $this->userService = $userService;
        $this->session = $session;
    }

    /**
     * Create a new user account
     *
     * @param array $data
     * @return Result
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

            return new Result(true);
        } catch (ValidationError $e) {
            return new Result(false, $e->errors);
        } catch (UserExists $e) {
            return new Result(false, [$e->field => $e->error]);
        } catch (NullDTOException $e) {
            return new Result(false, ["global" => "Something went wrong. Try again."]);
        }
    }

    public function loginWithPassword(string $identifier, string $password): Result
    {
        try {
            $this->session->storeLoginDetails($this->userService->loginWithPassword($identifier, $password));
            return new Result(true);
        } catch (NonExistentUser | PasswordMismatch $e) {
            return new Result(false, $e->getMessage());
        }
    }
}