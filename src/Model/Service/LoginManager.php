<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Service;


use Skletter\Contract\Entity\Identity;
use Skletter\Exception\Domain\PasswordMismatch;
use Skletter\Model\Entity\StandardIdentity;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginManager
{
    /**
     * @var Session $session
     */
    private $session;


    /**
     * LoginManager constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param StandardIdentity $identity
     * @param string $password
     * @throws PasswordMismatch
     */
    public function loginWithPassword(StandardIdentity $identity, string $password)
    {
        if (password_verify($password, $identity->getHashedPassword()))
            $this->login($identity);
        else
            throw new PasswordMismatch('The password you entered is invalid');
    }

    /**
     * Sets the login state, by updating db and logging data
     * @param Identity $identity
     */
    public function login(Identity $identity)
    {
        $this->session->set('UserID', $identity->getId());
        // Log stuff here
    }

}