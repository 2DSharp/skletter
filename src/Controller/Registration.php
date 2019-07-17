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
use Skletter\Model\Entity\StandardIdentity;
use Skletter\Model\Service;
use Symfony\Component\HttpFoundation\Request;

class Registration implements Controller
{
    /**
     * @var Service\RegistrationManager $manager
     */
    private $manager;

    public function __construct(Service\RegistrationManager $manager)
    {
        $this->manager = $manager;
    }

    public function registerUser(Request $request): void
    {
        /** @var StandardIdentity $identity */
        $this->manager->registerIdentity(
            $request->request->get('email'),
            $request->request->get('username'),
            $request->request->get('password'));

        $this->manager->registerProfile($request->request->get('name'),
            $request->request->get('locale'),
            $request->request->get('Birthday'));

        $this->manager->save();



    }

    public function handleRequest(Request $request, string $method): void
    {
        $this->{$method}($request);
    }
}