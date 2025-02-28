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


use Skletter\Model\Mediator\SearchService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Search extends AbstractView
{
    private SearchService $search;

    public function __construct(SearchService $search)
    {
        $this->search = $search;
    }

    /**
     * Redirect to correct location on success otherwise show error messages
     *
     * @param  Request $request
     * @return Response
     */
    public function look(Request $request): Response
    {
        $res = $this->search->suggest($request->query->get("q"));
        return new JsonResponse($res);
    }
}