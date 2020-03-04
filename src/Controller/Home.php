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
use Skletter\Exception\InvalidCookie;
use Skletter\Model\LocalService\CookieManager;
use Skletter\Model\LocalService\SessionManager;
use Skletter\Model\Mediator\AccountService;
use Symfony\Component\HttpFoundation\Request;

class Home implements Controller
{
    use ControllerTrait;
    /**
     * @var SessionManager
     */
    private SessionManager $sessionManager;
    /**
     * @var AccountService
     */
    private AccountService $accountService;

    public function __construct(SessionManager $sessionManager, AccountService $accountService)
    {
        $this->sessionManager = $sessionManager;
        $this->accountService = $accountService;
    }

    public function main(Request $request)
    {
        try {

            if (!$this->sessionManager->isLoggedIn()) {
                $token = $request->cookies->get($_ENV['PERSISTENCE_COOKIE'], null);
                if (!is_null($token)) {
                    $params = ['User-Agent' => $request->headers->get('User-Agent')];
                    $cookieDTO = CookieManager::getLoginCookie($token, $params);
                    $this->accountService->loginWithCookie($cookieDTO, $params);
                }
            }
        } catch (InvalidCookie $e) {
            //log this
        }
    }

    public function handleRequest(Request $request, string $method): void
    {
        $this->{$method}($request);
    }
}