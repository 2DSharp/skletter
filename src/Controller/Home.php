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
use Skletter\Component\SecureTokenManager;
use Skletter\Exception\Domain\UserDoesNotExistException;
use Skletter\Exception\InvalidCookie;
use Skletter\Model\Mediator\IdentityMap;
use Skletter\Model\Mediator\LoginManager;
use Symfony\Component\HttpFoundation\Request;

class Home implements Controller
{
    private $loginManager;
    private $map;

    public function __construct(LoginManager $loginManager, IdentityMap $map)
    {
        $this->loginManager = $loginManager;
        $this->map = $map;
    }

    public function mainBak(Request $request)
    {

        try {
            if (!$this->loginManager->isLoggedIn()) {
                $token = $request->cookies->get('uid', 'none');
                // check for tampering before hitting the DB

                if ($token == 'none') {
                    return;
                }

                if (SecureTokenManager::isTampered($token)) {
                    $this->loginManager->logout();
                }

                $this->loginManager->loginWithCookie($token);
            }

        } catch (UserDoesNotExistException | InvalidCookie $e) {
            // Cookie wasn't found, fall back
            $this->loginManager->logout();
        }

    }

    public function handleRequest(Request $request, string $method): void
    {
        $this->{$method}($request);
    }
}