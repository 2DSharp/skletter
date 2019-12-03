<?php

namespace Skletter\Component\Core;

use Auryn\InjectionException;
use Auryn\Injector;
use Skletter\Component\Core\Exception\NoHandlerSpecifiedException;
use Symfony\Component\HttpFoundation\Request;

final class Application
{
    private Injector $injector;

    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    /**
     * @param $request
     * @param RouteVOInterface $route
     * @throws InjectionException
     * @throws NoHandlerSpecifiedException
     */
    public function run(Request $request, RouteVOInterface $route): void
    {
        $controllerResource = $route->resolveController();
        $controllerMethod = $route->resolveControllerMethod();

        $viewResource = $route->resolveView();
        $viewMethod = $route->resolveViewMethod();

        $exists = false;

        if (method_exists($controllerResource, $controllerMethod)) {
            /**
             * @var Controller $controller
             */
            $controller = $this->injector->make($controllerResource);
            $dto = $controller->handleRequest($request, $controllerMethod);
            $exists = true;
        }
        if (method_exists($viewResource, $viewMethod)) {
            /**
             * @var View $view
             */
            $view = $this->injector->make($viewResource);
            if (isset($dto))
                $view->createResponse($request, $viewMethod, $dto)->send();
            else
                $view->createResponse($request, $viewMethod)->send();

            $exists = true;
        }

        if (!$exists)
            throw new NoHandlerSpecifiedException($controllerResource, $viewResource,
                                                  $controllerMethod . "/" . $viewMethod);
    }

}