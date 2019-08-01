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
use Skletter\Model\Entity\CookieIdentity;
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
     * @var CookieManager $cookieManager
     */
    private $cookieManager;

    /**
     * LoginManager constructor.
     * @param SessionInterface $session
     * @param CookieManager $cookieManager
     */
    public function __construct(SessionInterface $session,
                                CookieManager $cookieManager)
    {
        $this->session = $session;
        $this->cookieManager = $cookieManager;
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
     * @param StandardIdentity $identity
     */
    public function login(StandardIdentity $identity)
    {
        $this->session->set('id', $identity->getId());
        $this->session->set('email', $identity->getEmail());
        $this->session->set('name', $identity->getUsername());
        $this->session->set('status', $identity->getStatus());
        // Log stuff here
    }

    /**
     * Generate a cookie for remembering the user based on the cookie identity
     * @param Identity $identity
     * @param \DateTimeImmutable $validTill
     * @return CookieIdentity
     * @throws \Skletter\Exception\InvalidCookie
     */
    public function remember(Identity $identity, \DateTimeImmutable $validTill): CookieIdentity
    {
        $cookie = $this->cookieManager->buildCookieIdentity($identity, $validTill);
        $this->cookieManager->store($cookie);
        return $cookie;
    }

    public function isLoggedIn(): bool
    {
        $id = $this->session->get('id', 'none');
        if ($id != 'none') {

        }
        return false;
    }
}