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


use Skletter\Model\RemoteService\Search\SearchClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Search extends AbstractView
{
    /**
     * @var Environment $twig
     */
    private $twig;
    private $search;

    public function __construct(Environment $twig, SearchClient $search)
    {
        $this->twig = $twig;
        $this->search = $search;
    }


    /**
     * Redirect to correct location on success otherwise show error messages
     *
     * @param  Request $request
     * @param array $dto
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function look(Request $request): Response
    {

        $res = $this->search->suggest($request->query->get("q"));

        foreach ($res as &$value) {
            $value = json_decode($value);
            $value->user->data = json_decode($value->user->data);
        }
        $response = new Response(json_encode($res));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function testSearch(Request $request): Response
    {
        $html = $this->twig->render('pages/search.twig');
        return new Response($html);
    }
}