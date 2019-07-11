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


use Greentea\Component\RouteVOInterface;

class RouteInformation implements RouteVOInterface
{
    /**
     * @var string $controller - Controller class path
     */
    private $controller;
    /**
     * @var string $view - View class path
     */
    private $view;
    /**
     * @var string $method - method to be invoked in both controller and interface
     */
    private $method;

    /**
     * RouteInformation constructor.
     * @param string $controller
     * @param string $view
     * @param string $method
     */
    public function __construct(string $controller, string $view, string $method)
    {
        $this->controller = $controller;
        $this->view = $view;
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function resolveController(): string
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function resolveView(): string
    {
        return $this->view;
    }

    /**
     * @return string
     */
    public function resolveMethod(): string
    {
        return $this->method;
    }
}