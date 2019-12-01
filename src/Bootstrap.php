<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Auryn\Injector;
use Greentea\Core\Application;
use Skletter\Component\FallbackExceptionHandler;
use Skletter\Component\Router;
use Skletter\Component\TransportCollector;
use Skletter\View\ErrorPages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Load environment variables
 */
$dotenv = Dotenv\Dotenv::create(__DIR__ . "/../app/config");
$dotenv->load();
/**
 * @var Injector $injector
 */
$injector = include_once __DIR__ . '/Dependencies.php';


$request = $injector->make(Request::class);
// TODO: Find a better name replacement for fallBackHandler
$provider = $injector->make(FallbackExceptionHandler::class);

$exceptionHandler = function ($exception) use ($provider, $request) {
    $provider->handle($exception, $request);
};

set_exception_handler($exceptionHandler);

$session = $injector->make(SessionInterface::class);
$session->start();

$routes = include_once __DIR__ . '/Routes.php';

$router = new Router($routes, ErrorPages::class);
$requestedRoute = $router->route($request, 'Skletter\Controller\\', 'Skletter\View\\');

$app = new Application($injector);
$app->run($request, $requestedRoute);

$collector = $injector->make(TransportCollector::class);
$collector->closeAll();