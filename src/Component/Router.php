<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Component;


use FastRoute\Dispatcher;
use Skletter\Component\Core\RouteVOInterface;
use Skletter\Component\ValueObject\RouteInformation;
use Skletter\Exception\InvalidErrorPage;
use Skletter\View\ErrorPageView;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Router
 *
 * @package Skletter
 *
 * This class works as a wrapper around FastRoute to implement the RouteInterface
 * It enables to extract the necessary information from the request to route to the correct \
 * handler.
 */
class Router
{
    /**
     * @var array $routes
     */
    private $routes;
    /**
     * @var $errorPage
     */
    private $errorPage;
    /**
     * @var array $errorPageMap , method mappings to unsuccessful routing paths
     */
    private $errorPageMap = [Dispatcher::NOT_FOUND => 'pageNotFound', Dispatcher::METHOD_NOT_ALLOWED => 'methodNotAllowed'];

    /**
     * RouteFactory constructor.
     *
     * @param  array $routes
     * @param  string $errorPage
     * @throws InvalidErrorPage
     */
    public function __construct(array $routes, string $errorPage)
    {
        $this->routes = $routes;
        $this->errorPage = $errorPage;

        if (!is_subclass_of($this->errorPage, ErrorPageView::class)) {
            throw new InvalidErrorPage("The error page class doesn't implement ErrorPageView");
        }
    }

    public function route(Request $request, string $controllerNamespace, string $viewNamespace): RouteVOInterface
    {
        $routeInfo = $this->getRouteInfo($this->routes, $request);

        $controller = null;

        if ($routeInfo[0] != Dispatcher::FOUND) {
            $viewMethod = $controllerMethod = $this->errorPageMap[$routeInfo[0]];
            $view = $this->errorPage;
        } else {
            $controllerMethod = $routeInfo[1][1];
            $viewMethod = (array_key_exists(2, $routeInfo[1])) ? $routeInfo[1][2] : $controllerMethod;
            $controller = $controllerNamespace . $routeInfo[1][0];
            $view = $viewNamespace . $routeInfo[1][0];
        }

        return new RouteInformation($controller, $view, $controllerMethod, $viewMethod);
    }

    private function getRouteInfo(array $routes, Request $request) : array
    {
        $routeDefinitionCallback = function (\FastRoute\RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $r->addRoute($route[0], $route[1], $route[2]);
            }
        };
        $dispatcher = \FastRoute\cachedDispatcher(
            $routeDefinitionCallback,
            [
                'cacheFile' => __DIR__ . '/../../app/cache/route.cache',
            ]
        );
        return $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
    }

}