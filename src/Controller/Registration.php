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
use Phypes\Type\Password;
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
    private $mailer;

    public function __construct(Service\RegistrationManager $registrationManager,
                                RegistrationState $state,
                                Service\TransactionalMailer $mailer,
                                Service\LoginManager $loginManager)
    {
        $this->manager = $registrationManager;
        $this->state = $state;
        $this->loginService = $loginManager;
        $this->mailer = $mailer;
    }

    /**
     * @param  Request $request
     * @throws \Phypes\Exception\InvalidRule
     * @throws \Exception
     */
    public function registerUser(Request $request): void
    {
        try {
            $password = $request->request->get('password');

            /**
             * @var StandardIdentity $identity
             */
            $this->manager->registerIdentity(
                $request->request->get('email'),
                $request->request->get('username'),
                $password
            );
            $this->manager->registerProfile(
                $request->request->get('name'),
                'IND',
                new \DateTimeImmutable()
            );
            $this->manager->save();

            $identity = $this->manager->getStandardIdentity();
            $this->mailer->sendAccountConfirmationEmail($identity->getId());
            $this->loginService->loginWithPassword($request->request->get('email'), new Password($password));

            $this->state->setSuccess(true);

        } catch (IdentifierExistsException | ValidationError | RegistrationFailure $e) {
            $this->state->setError($e->getMessage());
        }
    }

    public function handleRequest(Request $request, string $method): void
    {
        $this->{$method}($request);
    }
}