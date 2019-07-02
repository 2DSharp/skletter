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

class Home extends View
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
    public function main(Request $request) : Response
    {
        $html = $this->createHTMLFromTemplate($this->templating, 'home.twig',
            ['title' => 'Skletter - Home']);
        return $this->respond($request, $html);
    }
}