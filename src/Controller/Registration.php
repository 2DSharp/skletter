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
use Skletter\Model\Mediator;
use Symfony\Component\HttpFoundation\Request;


class Registration implements Controller
{
    use ControllerTrait;

    private Mediator\AccountService $account;

    public function __construct(Mediator\AccountService $account)
    {
        $this->account = $account;
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function registerUser(Request $request)
    {
        $account = [
            'name' => $request->request->get('name'),
            'username' => $request->request->get('username'),
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'ip-address' => $request->getClientIp()
        ];

        $result = $this->account->register($account);
        $loginResult = null;

        if ($result->isSuccess()) {
            $meta = [
                'ip-address' => $request->getClientIp(),
                'user-agent' => $request->headers->get('User-Agent')
            ];
            $loginResult = $this->account->loginWithPassword($account['email'], $account['password'], $meta);
            return ['success' => $result->isSuccess(), 'cookie' => $loginResult->getValueObject()];
        }

        return ['success' => $result->isSuccess(), 'errors' => $result->getErrors()];
    }
}