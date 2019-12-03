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

    public function handleRequest(Request $request, string $method): void
    {
        $this->{$method}($request);
    }
}