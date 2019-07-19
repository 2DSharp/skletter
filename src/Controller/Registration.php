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
use Phypes\Exception\EmptyRequiredValue;
use Skletter\Exception\IdentifierExistsException;
use Skletter\Exception\ValidationError;
use Skletter\Model\DTO\RegistrationState;
use Skletter\Model\Entity\StandardIdentity;
use Skletter\Model\Service;
use Symfony\Component\HttpFoundation\Request;

class Registration implements Controller
{
    /**
     * @var Service\RegistrationManager $manager
     */
    private $manager;
    /**
     * @var RegistrationState $state
     */
    private $state;

    public function __construct(Service\RegistrationManager $manager, RegistrationState $state)
    {
        $this->manager = $manager;
        $this->state = $state;
    }

    /**
     * @param Request $request
     * @throws \Phypes\Exception\InvalidRule
     */
    public function registerUser(Request $request): void
    {
        try {
            /** @var StandardIdentity $identity */
            $this->manager->registerIdentity(
                $request->request->get('email'),
                $request->request->get('username'),
                $request->request->get('password'));

            $this->manager->registerProfile($request->request->get('name'),
                'IND',
                new \DateTimeImmutable());

            $this->manager->save();

            $nonce = $this->manager->getNonceIdentity();
            $identity = $this->manager->getStandardIdentity();
            $this->state->setStatus('success');

        } catch (EmptyRequiredValue | IdentifierExistsException | ValidationError $e) {
            $this->state->setStatus('failure');
            $this->state->setError($e->getMessage());
        }


    }

    public function handleRequest(Request $request, string $method): void
    {
        $this->{$method}($request);
    }
}