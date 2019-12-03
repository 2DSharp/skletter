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


use Skletter\Component\Core\Controller;
use Skletter\Model\Mediator\AccountService;
use Symfony\Component\HttpFoundation\Request;

class Login implements Controller
{
    use ControllerTrait;
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
     * @throws \Exception
     */
    public function attemptLogin(Request $request)
    {
        $identifier = $request->request->get('identity');
        $password = $request->request->get('password');

        $meta = [
            'ip-address' => $request->getClientIp(),
            'user-agent' => $request->headers->get('User-Agent')
        ];
        $result = $this->account->loginWithPassword($identifier, $password, $meta);

        if ($result->isSuccess())
            return ['success' => true, 'cookie' => $result->getValueObject()];
        else
            return ['success' => false, 'errors' => $result->getErrors()];
    }
}