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
use Skletter\Component\Router;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Load environment variables
 */
$dotenv = Dotenv\Dotenv::create(__DIR__ . "/../app/config");
$dotenv->load();
/**
 * @var \Auryn\Injector $injector
 */
$injector = require_once(__DIR__.'/Dependencies.php');

$request = $injector->make(\Symfony\Component\HttpFoundation\Request::class);
// TODO: Find a better name replacement for fallBackHandler
$fallBackHandler = $injector->make(\Skletter\Component\FallbackExceptionHandler::class);

$exceptionHandler = function ($exception) use ($fallBackHandler, $request) {
    $fallBackHandler->handle($exception, $request);
};

set_exception_handler($exceptionHandler);

$routes = require_once(__DIR__ . '/Routes.php');

$router = new Router($routes, \Skletter\View\ErrorPages::class);
$requestedRoute = $router->route($request, 'Skletter\Controller\\', 'Skletter\View\\');

$app = new Application($injector);
$app->run($request, $requestedRoute);