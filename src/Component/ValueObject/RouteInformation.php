<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Component\ValueObject;


use Skletter\Component\Core\RouteVOInterface;

class RouteInformation implements RouteVOInterface
{
    /**
     * @var string $controller - Controller class path
     */
    private ?string $controller;
    /**
     * @var string $view - View class path
     */
    private ?string $view;
    /**
     * @var string $controllerMethod - method to be invoked in both controller and interface
     */
    private string $controllerMethod;
    /**
     * @var string|null
     *
     */
    private ?string $viewMethod;

    /**
     * RouteInformation constructor.
     *
     * @param string $controller
     * @param string $view
     * @param string $controllerMethod
     * @param string $viewMethod
     */
    public function __construct(?string $controller, ?string $view, string $controllerMethod, string $viewMethod)
    {
        $this->controller = $controller;
        $this->view = $view;
        $this->controllerMethod = $controllerMethod;
        $this->viewMethod = $viewMethod;
    }

    public function resolveController(): ?string
    {
        return $this->controller;
    }

    public function resolveView(): ?string
    {
        return $this->view;
    }

    public function resolveControllerMethod(): string
    {
        return $this->controllerMethod;
    }

    public function resolveViewMethod(): string
    {
        return $this->viewMethod;
    }

}