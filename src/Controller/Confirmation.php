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
use Skletter\Model\Mediator\SearchService;
use Skletter\Model\RemoteService\DTO\UserDTO;
use Symfony\Component\HttpFoundation\Request;

class Confirmation implements Controller
{
    use ControllerTrait;
    /**
     * @var SearchService
     */
    private SearchService $search;
    /**
     * @var AccountService
     */
    private AccountService $accountService;

    public function __construct(AccountService $accountService, SearchService $search)
    {
        $this->accountService = $accountService;
        $this->search = $search;
    }

    public function confirmRegistrationWithToken(Request $request)
    {
        $id = $request->query->get("uid", -1);
        $token = $request->query->get("token", "0");

        $result = $this->accountService->confirmAccount($id, $token, AccountService::CONFIRMATION_TOKEN);
        /**
         * @var UserDTO $user
         */
        $user = $result->getValueObject();
        $this->search->initiateIndexing($user);
        return ['success' => $result->isSuccess(), 'username' => $user->username, 'errors' => $result->getErrors()];
    }

    public function confirmRegistrationWithPin(Request $request)
    {
        $id = $this->accountService->getSessionUser()->id;
        $token = $request->request->get("token", "0");

        $result = $this->accountService->confirmAccount($id, $token, AccountService::CONFIRMATION_PIN);
        /**
         * @var UserDTO $user
         */
        $user = $result->getValueObject();
        $this->search->initiateIndexing($user);

        return ['success' => $result->isSuccess(), 'errors' => $result->getErrors()];
    }
}