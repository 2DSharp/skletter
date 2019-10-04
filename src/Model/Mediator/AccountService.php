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

use Skletter\Factory\CookieFactory;
use Skletter\Model\LocalService\SessionManager;
use Skletter\Model\RemoteService\DTO\LoginMetadata;
use Skletter\Model\RemoteService\DTO\UserDTO;
use Skletter\Model\RemoteService\Exception\NonExistentUser;
use Skletter\Model\RemoteService\Exception\NullDTOException;
use Skletter\Model\RemoteService\Exception\PasswordMismatch;
use Skletter\Model\RemoteService\Exception\UserExists;
use Skletter\Model\RemoteService\Exception\ValidationError;
use Skletter\Model\RemoteService\Romeo\RomeoClient;
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
     * @var RomeoClient $userService
     */
    private $userService;
    /**
     * Local session management service
     * @var SessionManager $session
     */
    private $session;

    /**
     * AccountService constructor.
     * @param RomeoClient $userService
     * @param SessionManager $session
     */
    public function __construct(RomeoClient $userService, SessionManager $session)
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
            $user->ipAddr = $data['ip-address'];

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

    public function loginWithPassword(string $identifier, string $password, array $params): Result
    {
        try {
            $meta = new LoginMetadata();
            $meta->ipAddr = $params['ip-address'];
            $meta->headers = $params['user-agent'];
            $meta->localSessionId = $this->session->getId();

            $cookieDTO = $this->userService->loginWithPassword($identifier, $password, $meta);
            $this->session->storeLoginDetails($cookieDTO->user);

            $cookie = CookieFactory::createSignedCookie($cookieDTO, "persistence", $params['user-agent']);

            $result = new Result(true);
            $result->setValueObject($cookie);

            return $result;

        } catch (NonExistentUser | PasswordMismatch $e) {
            return new Result(false, $e->getMessage());
        }
    }
}