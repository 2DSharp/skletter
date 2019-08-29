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
use Skletter\Exception\Domain\AuthenticationFailure;
use Skletter\Model\RemoteService\Exception\NonExistentUser;
use Skletter\Model\ServiceMediator\SessionManager;
use Symfony\Component\HttpFoundation\Request;

class Login implements Controller
{
    /**
     * LoginManager service to handle authentication and log in system
     *
     * @var SessionManager $sessionManager
     */
    private $sessionManager;

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    /**
     * @param Request $request
     * @return array
     * @request_type POST
     */
    public function attemptLogin(Request $request)
    {
        try {
            $identifier = $request->request->get('identity');
            $password = $request->request->get('password');

            $token = $this->sessionManager->loginWithPassword($identifier, $password, $request->headers->get('HTTP_USER_AGENT'));
            return ['success' => true, 'cookie' => $token];

        } catch (NonExistentUser | AuthenticationFailure $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function handleRequest(Request $request, string $method): array
    {
        return $this->{$method}($request);
    }
}