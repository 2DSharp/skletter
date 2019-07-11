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

class Home extends AbstractView
{
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
    private function main(Request $request): Response
    {
        $html = $this->createHTMLFromTemplate($this->templating, 'home2.twig',
            ['title' => 'Skletter - Home']);
        return $this->respond($request, $html);
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