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


use Greentea\Core\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

abstract class AbstractView implements View
{
    protected function respond(Request $request, $html, int $status = 200)
    {
        $response = new Response($html, $status);
        $response->prepare($request);
        return $response;
    }

    /**
     * @param Environment $twig
     * @param string $template
     * @param array $params
     * @return Response
     * @throws \Twig\Error\Error
     */
    protected function createHTMLFromTemplate(Environment $twig, string $template, $params = [])  : string
    {
        return $html = $twig->render($template, $params);
    }

    /**
     * Redirect the user on success, or let JS handle it in case its an AJAX request
     * @param Request $request
     * @param array $params
     * @param string $redirectLocation
     * @return Response
     */
    protected function sendSuccessResponse(Request $request, array $params, string $redirectLocation): Response
    {
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($jsonResponse);
        }
        return new RedirectResponse($redirectLocation);
    }

    /**
     * Send a response with error messages
     * @param Request $request
     * @param Environment $twig
     * @param array $params
     * @param string $template
     * @return Response
     * @throws \Twig\Error\Error
     */
    protected function sendFailureResponse(Request $request, Environment $twig, array $params, string $template): Response
    {
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($params);
        }
        return $this->respond($request, $this->createHTMLFromTemplate($twig, $template, $params));
    }

}