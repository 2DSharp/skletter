<?php declare(strict_types = 1);
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Skletter;

use Auryn\Injector;
use Symfony\Component\HttpFoundation\Request;

$injector = new Injector;
/**
 * Dependencies go here
 * Add factories by delegating functions to their ctors
 */
$requestFactory = function() {
    $obj = Request::createFromGlobals();

    return $obj;
};
$injector->delegate(Request::class, $requestFactory);

return $injector;
