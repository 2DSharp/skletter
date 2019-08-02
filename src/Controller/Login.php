<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Controller;


use Greentea\Core\Controller;
use Phypes\Exception\EmptyRequiredValue;
use Phypes\Exception\InvalidValue;
use Phypes\Type\Password;
use Phypes\Type\StringRequired;
use Skletter\Component\UserFriendlyError;
use Skletter\Exception\Domain\PasswordMismatch;
use Skletter\Exception\Domain\UserDoesNotExistException;
use Skletter\Exception\InvalidIdentifier;
use Skletter\Model\DTO\LoginState;
use Skletter\Model\Entity\StandardIdentity;
use Skletter\Model\Service\IdentityMap;
use Skletter\Model\Service\LoginManager;
use Symfony\Component\HttpFoundation\Request;

class Login implements Controller
{
    /**
     * LoginManager service to handle authentication and log in system
     * @var LoginManager $loginManager
     */
    private $loginManager;
    /**
     * Service to look up identity based on some identifier and build a valid instance
     * @var IdentityMap $lookup
     */
    private $lookup;
    /**
     * Data Transfer Object to carry forward the login data to the view
     * @var LoginState
     */
    private $state;

    public function __construct(LoginManager $loginManager, IdentityMap $lookup, LoginState $state)
    {
        $this->loginManager = $loginManager;
        $this->lookup = $lookup;
        $this->state = $state;
    }

    /**
     * @param Request $request
     * @request_type POST
     * @throws \Phypes\Exception\InvalidRule
     */
    public function attemptLogin(Request $request)
    {
        try {
            $identifier = $request->request->get('identity');
            $rawPassword = new Password(new StringRequired($request->request->get('password')));
            /**
             * @var StandardIdentity $identity
             */
            $identity = $this->lookup->getStandardIdentity($identifier);

            // Set session data, log stuff, update db
            $this->loginManager->loginWithPassword($identity, $rawPassword);
            $this->state->setSuccess(true);

        } catch (UserDoesNotExistException | InvalidIdentifier $exception) {
            $this->state->setError(UserFriendlyError::getError(UserFriendlyError::NONEXISTENT_IDENTIFIER));

        } catch (PasswordMismatch | InvalidValue $e) {
            $this->state->setError(UserFriendlyError::getError(UserFriendlyError::INVALID_PASSWORD_VAGUE));
        } catch (EmptyRequiredValue $e) {
            $this->state->setError('You must fill in the all the fields');
        }

    }

    public function handleRequest(Request $request, string $method): void
    {
        $this->{$method}($request);
    }
}