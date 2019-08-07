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
use Skletter\Model\Service\IdentityMap;
use Skletter\Model\Service\LoginManager;
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

    public function main(Request $request)
    {
        if (!$this->loginManager->isLoggedIn()) {
            $token = $request->cookies->get('uid');
            // check for tampering before hitting the DB
            if (SecureTokenManager::isTampered($token))
                $this->loginManager->logout();

            $identity = $this->map->getCookieIdentity($token);
            $this->loginManager->loginWithCookie($identity);
        }

    }

    public function handleRequest(Request $request, string $method): void
    {
        $this->{$method}($request);
    }
}