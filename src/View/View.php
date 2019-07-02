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
use Twig\Environment;

abstract class View
{
    protected function respond(Request $request, $html,  int $status = 200) {
        $response = new Response($html, $status);
        $response->prepare($request);
        return $response;
    }

    /**
     * @param Environment $twig
     * @param string $template
     * @param array $params
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function createHTMLFromTemplate(Environment $twig, string $template, $params = [])  : string
    {
        return $html = $twig->render($template, $params);
    }
}