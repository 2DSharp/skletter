<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Component\Core\Exception;


class NoHandlerSpecifiedException extends \Exception
{
    public function __construct($controller, $view, $method)
    {
        parent::__construct("Could not find handler specified for this action. Controller: " . $controller . ", View: " . $view .
                            " Method:" . $method);
    }
}