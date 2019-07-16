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
use Skletter\Model\Service;
use Symfony\Component\HttpFoundation\Request;

class Registration implements Controller
{
    /**
     * @var Service\IdentityManager $manager
     */
    private $manager;

    public function __construct(Service\IdentityManager $manager)
    {
        $this->manager = $manager;
    }

    public function registerUser(Request $request): void
    {
        $this->manager->createStandardIdentity($request->request->get('email'),
            $request->request->get('username'),
            $request->request->get('password'));
    }

    public function handleRequest(Request $request, string $method): void
    {
        $this->{$method}($request);
    }
}