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
use Skletter\Model\RemoteService\Exception\UserExists;
use Skletter\Model\RemoteService\Exception\ValidationError;
use Skletter\Model\ServiceMediator;
use Symfony\Component\HttpFoundation\Request;


class Registration implements Controller
{
    /**
     * @var ServiceMediator\SessionManager $sessionManager
     */
    private $sessionManager;
    private $mailer;
    private $registration;

    public function __construct(ServiceMediator\AccountService $registration,
                                ServiceMediator\TransactionalMailer $mailer,
                                ServiceMediator\SessionManager $sessionManager)
    {
        $this->registration = $registration;
        $this->sessionManager = $sessionManager;
        $this->mailer = $mailer;
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Skletter\Model\RemoteService\Exception\NonExistentUser
     */
    public function registerUser(Request $request)
    {
        try {
            $account = [
                'name' => $request->request->get('name'),
                'username' => $request->request->get('username'),
                'email' => $request->request->get('email'),
                'password' => $request->request->get('password')
            ];
            $this->registration->register($account);

            $this->mailer->sendAccountConfirmationEmail($account['email']);

            $this->sessionManager->loginWithPassword(
                $account['email'],
                $account['password'],
                $request->headers->get('HTTP_USER_AGENT')
            );

            return ['success' => true];

        } catch (UserExists | ValidationError $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function handleRequest(Request $request, string $method): array
    {
        return $this->{$method}($request);
    }
}