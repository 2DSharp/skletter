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
use Symfony\Component\HttpFoundation\Response;

class ErrorPages extends View implements ErrorPageView
{

    public function pageNotFound(Request $request) : Response
    {
        return $this->respond($request, 'Page Not Found', 404);
    }

    public function methodNotAllowed(Request $request) : Response
    {
        return $this->respond($request, 'Method not allowed', 401);
    }

    public function internalError(Request $request) : Response
    {
        return $this->respond($request, 'Internal Server error', 500);
    }

}