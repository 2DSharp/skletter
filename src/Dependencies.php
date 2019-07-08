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
use Skletter\Component\FallbackExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use function Skletter\Factory\buildLazyLoader;
use function Skletter\Factory\getLazyLoadingTwigFactory;
use function Skletter\Factory\getRequestFactory;

$injector = new Injector;
/**
 * Dependencies go here
 * Add factories by delegating functions to their ctors
 */

$injector->delegate(Request::class, getRequestFactory());

$lazyloader = buildLazyLoader(__DIR__ . '/../app/cache/proxies');

$templatesDir = __DIR__ . '/../templates';
$templatesCacheDir = __DIR__ . '/../app/cache/templates';

$injector->delegate(Environment::class, getLazyLoadingTwigFactory($lazyloader, $templatesDir, $templatesCacheDir));
$injector->share(Environment::class);

$injector->define(FallbackExceptionHandler::class,
    [':logConfig' => ['LOG_FILE' => __DIR__ . '/../app/logs/error.log']]);

return $injector;


