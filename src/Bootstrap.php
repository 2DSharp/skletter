<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Greentea\Core\Application;
use Skletter\RouteFactory;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Load environment variables
 */
$dotenv = Dotenv\Dotenv::create(__DIR__ . "/../app/config", 'env_vars');
$dotenv->load();
/**
 * @var \Auryn\Injector $injector
 */
$injector = require_once(__DIR__.'/Dependencies.php');

$request = $injector->make(\Symfony\Component\HttpFoundation\Request::class);

$fallBackHandler = $injector->make(\Skletter\Component\FallbackExceptionHandler::class);

$exceptionHandler = function ($exception) use ($fallBackHandler, $request) {
    $fallBackHandler->handle($exception, $request);
};

set_exception_handler($exceptionHandler);

$routes = require_once(__DIR__ . '/Routes.php');

$router = new RouteFactory($routes, $request, \Skletter\View\ErrorPages::class);
$router->buildPaths('Skletter\Controller\\', 'Skletter\Views\\');

$app = new Application($injector);
$app->run($request, $router);