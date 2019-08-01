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
use Skletter\Contract\Repository\IdentityRepositoryInterface;
use Skletter\Factory\IdentityFactory;
use Skletter\Model\Entity\CookieIdentity;

class CookieManager
{
    private $repository;
    private $factory;

    public function __construct(IdentityFactory $factory, IdentityRepositoryInterface $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * TODO: Check for uniqueness
     * @param CookieIdentity $cookie
     * @return void
     */
    public function store(CookieIdentity $cookie): void
    {
        $this->repository->save($cookie);
    }

    /**
     * @param Identity $identity
     * @param \DateTimeImmutable $validTill
     * @return CookieIdentity
     * @throws \Skletter\Exception\InvalidCookie
     */
    public function buildCookieIdentity(Identity $identity, \DateTimeImmutable $validTill): CookieIdentity
    {
        return $this->factory->createCookieIdentity($identity, $validTill);
    }
}