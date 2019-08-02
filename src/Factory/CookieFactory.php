<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Factory;


use Skletter\Model\Entity\CookieIdentity;
use Symfony\Component\HttpFoundation\Cookie;

class CookieFactory
{
    public static function createFromCookieIdentity(CookieIdentity $identity): Cookie
    {
        return Cookie::create($identity->getToken(), $identity->getValidTill());
    }
}