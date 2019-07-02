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


use Greentea\Exception\TemplatingException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ErrorPages extends AbstractView implements ErrorPageView
{
    // TODO: Avoid using Twig for generating Error pages. Use regular PHP within html to avoid Twig Errors from the error pages.
    private $templating;

    public function __construct(\Twig\Environment $twig)
    {
        $this->templating = $twig;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function pageNotFound(Request $request) : Response
    {
        $html = $this->createHTMLFromTemplate($this->templating, 'error.twig',
            ['title' => 'Page not found!',
                'message' => "Whoops! The page you requested either doesn't exist or you don't have the permission to view it."]);
        return $this->respond($request, $html, 404);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function methodNotAllowed(Request $request) : Response
    {
        $html = $this->createHTMLFromTemplate($this->templating, 'error.twig',
            ['title' => 'Invalid Method',
                'message' => "The requested method is not allowed for this page."]);

        return $this->respond($request, $html, 401);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function internalError(Request $request) : Response
    {
        $html = $this->createHTMLFromTemplate($this->templating, 'error.twig',
            ['title' => 'Something went wrong',
                'message' => "It's not you, it's us.\nWe ran into some problem and are fixing the problem as we speak."]);
        return $this->respond($request, $html, 500);
    }

    /**
     * @param Request $request
     * @param string $method
     * @return Response
     * @throws TemplatingException
     */
    public function createResponse(Request $request, string $method): Response
    {
        try {
            return $this->{$method}($request);
        } catch (\Twig\Error\Error $e) {
            throw new TemplatingException($e->getMessage(), $e->getCode(), $e);
        }
    }
}