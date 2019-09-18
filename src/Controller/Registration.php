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
use Skletter\Model\LocalService\SessionManager;
use Skletter\Model\Mediator;
use Symfony\Component\HttpFoundation\Request;


class Registration implements Controller
{

    private $session;
    private $mailer;
    private $account;

    public function __construct(Mediator\AccountService $account,
                                Mediator\TransactionalMailer $mailer,
                                SessionManager $session)
    {
        $this->account = $account;
        $this->session = $session;
        $this->mailer = $mailer;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function registerUser(Request $request)
    {
        $account = [
            'name' => $request->request->get('name'),
            'username' => $request->request->get('username'),
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'ipAddr' => $request->getClientIp()
        ];

        $result = $this->account->register($account);
        if ($result->isSuccess()) {
            $this->mailer->sendAccountConfirmationEmail($account['email']);
            $this->session->storeLoginDetails($this->account->loginWithPassword($account['email'],
                                                                                $account['password']));
        }

        return ['success' => $result->isSuccess(), 'errors' => $result->getErrors()];
    }

    public function handleRequest(Request $request, string $method): array
    {
        return $this->{$method}($request);
    }
}