<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Auryn\InjectionException;
use Greentea\Core\Application;

use Greentea\Exception\NoHandlerSpecifiedException;
use Skletter\Exception\InvalidErrorPage;
use Skletter\RouteFactory;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require_once __DIR__ . '/../vendor/autoload.php';

$injector = require_once(__DIR__.'/Dependencies.php');
$request = $injector->make(\Symfony\Component\HttpFoundation\Request::class);

try
{
    $routes = require_once(__DIR__.'/Routes.php');

    $router = new RouteFactory($routes, $request, \Skletter\View\ErrorPages::class);
    $router->buildPaths('Skletter\Controller\\', 'Skletter\View\\');

    $app = new Application($injector);
    $app->run($request, $router);
}
catch (InjectionException | InvalidErrorPage | NoHandlerSpecifiedException $e)
{
    $log = new Logger('Resolution');
    try
    {
        $log->pushHandler(new StreamHandler(__DIR__ . '/../app/logs/error.log', Logger::CRITICAL));
        $log->addCritical($e->getMessage(),
            array(
                'Stack Trace' => $e->getTraceAsString()
            ));
    }
    catch (Exception $e)
    {
        echo "No access to log file: ". $e->getMessage();
        // Handle this exception by pushing to db or emailing
    }
    finally
    {
        /**
         * @var \Skletter\View\ErrorPageView $errorPage
         */
        $errorPage = $injector->make(\Skletter\View\ErrorPages::class);
        $errorPage->internalError($request)->send();
    }
}
