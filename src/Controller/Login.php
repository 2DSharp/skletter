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
use Skletter\Model\Mediator\AccountService;
use Skletter\Model\RemoteService\Exception\NonExistentUser;
use Symfony\Component\HttpFoundation\Request;

class Login implements Controller
{
    /**
     * LoginManager service to handle authentication and log in system
     *
     * @var AccountService $account
     */
    private $account;

    public function __construct(AccountService $account)
    {
        $this->account = $account;
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

            // NEED TO PASS A METADATA DTO

            $token = $this->account->loginWithPassword($identifier, $password,
                                                       $request->headers->get('HTTP_USER_AGENT'));
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