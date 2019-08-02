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
     * @var Service\LoginManager $loginService
     */
    private $loginService;

    public function __construct(Service\RegistrationManager $registrationManager,
                                RegistrationState $state,
                                Service\LoginManager $loginManager)
    {
        $this->manager = $registrationManager;
        $this->state = $state;
        $this->loginService = $loginManager;
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

            $this->loginService->login($identity);

            // remember the user
            $this->loginService->remember($identity, $this->getExpiryDate());
            $this->state->setSuccess(true);

        } catch (IdentifierExistsException | ValidationError | RegistrationFailure $e) {
            $this->state->setError($e->getMessage());
        }
    }

    /**
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    private function getExpiryDate(): \DateTimeImmutable
    {
        $now = new \DateTimeImmutable();
        return $now->add(new \DateInterval("P2D"));
    }
    public function handleRequest(Request $request, string $method): void
    {
        $this->{$method}($request);
    }
}