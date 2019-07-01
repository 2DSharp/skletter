<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter;


use FastRoute\Dispatcher;
use Greentea\Component\RouteInterface;
use Skletter\Exception\InvalidErrorPage;
use Skletter\View\ErrorPageView;
use Symfony\Component\HttpFoundation\Request;

class RouteFactory implements RouteInterface
{
    /**
     * @var string $className
     */
    private $className;
    /**
     * @var string $controller
     */
    private $controller;
    /**
     * @var string|null $view
     */
    private $view;
    /**
     * @var string|null $method
     */
    private $method;

    private $errorPageMap = [Dispatcher::NOT_FOUND => 'pageNotFound', Dispatcher::METHOD_NOT_ALLOWED => 'methodNotAllowed'];

    /**
     * RouteFactory constructor.
     * @param array $routes
     * @param Request $request
     * @param string $errorPage
     * @throws InvalidErrorPage
     */
    public function __construct(array $routes, Request $request, string $errorPage)
    {
        $routeInfo = $this->getRouteInfo($routes, $request);

        $this->className = $routeInfo[1][0];
        $this->method = $routeInfo[1][1];

        if ($routeInfo[0] != Dispatcher::FOUND) {
            if (is_subclass_of($errorPage, ErrorPageView::class)) {
                $this->view = $errorPage;
                $this->method = $this->errorPageMap[$routeInfo[0]];
            }
            else
                throw new InvalidErrorPage("The error page class don't implement ErrorPageView");
        }
    }

    public function buildPaths(string $controllerNamespace, string $viewNamespace) : void
    {
        $this->controller = $controllerNamespace . $this->className;
        $this->view = $viewNamespace . $this->className;
    }

    private function getRouteInfo(array $routes, Request $request) : array
    {
        $routeDefinitionCallback = function (\FastRoute\RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $r->addRoute($route[0], $route[1], $route[2]);
            }
        };
        $dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);
        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

        return $routeInfo;
    }
    public function resolveController(): ?string
    {
        return $this->controller;
    }

    public function resolveView(): ?string
    {
        return $this->view;
    }

    public function resolveMethod(): string
    {
        return $this->method;
    }
}