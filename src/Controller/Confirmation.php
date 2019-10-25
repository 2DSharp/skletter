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
use Skletter\Model\Mediator\AccountService;
use Symfony\Component\HttpFoundation\Request;

class Confirmation implements Controller
{
    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function confirmRegistrationWithToken(Request $request)
    {
        $id = $request->query->get("uid", -1);
        $token = $request->query->get("token", "0");

        $result = $this->accountService->confirmAccount($id, $token, AccountService::CONFIRMATION_TOKEN);

        return ['success' => $result->isSuccess(), 'email' => $request->query->get('email'), 'errors' => $result->getErrors()];
    }

    public function confirmRegistrationWithPin(Request $request)
    {
        $id = $request->query->get("uid", -1);
        $token = $request->query->get("token", "0");

        $result = $this->accountService->confirmAccount($id, $token, AccountService::CONFIRMATION_PIN);

        return ['success' => $result->isSuccess(), 'errors' => $result->getErrors()];
    }

    public function handleRequest(Request $request, string $method)
    {
        return $this->{$method}($request);
    }
}