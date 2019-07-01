<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\View;


use Symfony\Component\HttpFoundation\Request;

class ErrorPages extends View implements ErrorPageView
{

    function pageNotFound(Request $request)
    {
        // TODO: Implement pageNotFound() method.
    }

    function methodNotAllowed(Request $request)
    {
        // TODO: Implement methodNotAllowed() method.
    }
}