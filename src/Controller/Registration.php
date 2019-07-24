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
use Skletter\Exception\Domain\RegistrationFailure;
use Skletter\Exception\Domain\ValidationError;
use Skletter\Exception\IdentifierExistsException;
use Skletter\Model\DTO\RegistrationState;
use Skletter\Model\Entity\StandardIdentity;
use Skletter\Model\Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
    /**
     * @var SessionInterface $session
     */
    private $session;

    public function __construct(Service\RegistrationManager $manager, RegistrationState $state, SessionInterface $session)
    {
        $this->manager = $manager;
        $this->state = $state;
        $this->session = $session;
    }

    /**
     * @param Request $request
     * @throws \Phypes\Exception\InvalidRule
     * @throws \Exception
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

            $this->session->set('id', $identity->getId());
            $this->session->set('email', $identity->getEmail());
            $this->session->set('name', $identity->getUsername());
            $this->session->set('status', $identity->getStatus());

            $this->state->setStatus('success');

        } catch (IdentifierExistsException | ValidationError | RegistrationFailure $e) {
            $this->state->setStatus('failure');
            $this->state->setError($e->getMessage());
        }
    }

    public function handleRequest(Request $request, string $method): void
    {
        $this->{$method}($request);
    }
}