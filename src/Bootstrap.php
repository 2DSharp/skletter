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

use function Skletter\getInjectorWithDependencies;
use Skletter\RouteFactory;

require_once __DIR__ . '../vendor/autoload.php';

$injector = getInjectorWithDependencies();


$app = new Application($injector);

try
{
    $request = $injector->make(\Symfony\Component\HttpFoundation\Request::class);
    $routes = require_once(__DIR__.'/Routes.php');

    $router = new RouteFactory($routes, $request, \Skletter\View\ErrorPages::class);
    $router->buildPaths('Skletter\Controller\\', 'Skletter\View\\');

    $app->run($request, $router);

}
catch (\Auryn\InjectionException $e) {
}
catch (\Skletter\Exception\InvalidErrorPage $e) {
}
