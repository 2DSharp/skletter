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
use Skletter\Exception\InvalidIdentifier;
use Skletter\Exception\InvalidPassword;
use Skletter\Exception\UserDoesntExistException;
use Skletter\Model\Entity\StandardIdentity;
use Skletter\Model\Service\IdentityLookup;
use Skletter\Model\Service\LoginManager;
use Skletter\Model\Service\PasswordMismatch;
use Symfony\Component\HttpFoundation\Request;

class Login implements Controller
{
    private $loginManager;
    private $identityLookup;

    public function __construct(LoginManager $loginManager, IdentityLookup $identityLookup)
    {
        $this->loginManager = $loginManager;
        $this->identityLookup = $identityLookup;
    }

    /**
     * @param Request $request
     * @request_type POST
     * @throws \Phypes\Exception\InvalidRule
     */
    public function attemptLogin(Request $request)
    {
        $identifier = $request->request->get('identity');
        $rawPassword = $request->request->get('password');

        try {
            /**
             * @var StandardIdentity $identity
             */
            $identity = $this->identityLookup->getStandardIdentity($identifier);

            // Set session data, log stuff, update db
            $this->loginManager->loginWithPassword($identity, $rawPassword);

        } catch (UserDoesntExistException | InvalidIdentifier $exception) {
            $this->loginManager->appendError('The username or email you have entered does not belong to any account.');

        } catch (PasswordMismatch | InvalidPassword $e) {
            $this->loginManager->appendError('The password you entered is incorrect');
        }

    }

    public function handleRequest(Request $request, string $method): void
    {
        $this->{$method}($request);
    }
}