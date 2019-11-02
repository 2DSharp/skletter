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


use Skletter\Model\LocalService\SessionManager;
use Skletter\Model\RemoteService\DTO\Status;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Home extends AbstractView
{
    private $templating;
    private $session;

    private $pageMap = [
        Status::TEMP => 'registration.twig',
        Status::ACTIVE => 'feed.twig'
    ];

    public function __construct(\Twig\Environment $twig, SessionManager $session)
    {
        $this->templating = $twig;
        $this->session = $session;
    }

    /**
     * @param  Request $request
     * @return Response
     * @throws \Twig\Error\Error
     */
    private function showLoggedOutHome(Request $request): Response
    {

        $html = $this->createHTMLFromTemplate(
            $this->templating, 'pages/home.twig',
            ['title' => 'Skletter - Home']
        );
        return $this->respond($request, $html);
    }

    private function getParams($status): array
    {
        switch ($status) {
            case Status::TEMP:
                return [
                    'title' => 'Confirm your email - Skletter',
                    'status' => 'Temp',
                    'email' => $this->session->getLoginDetails()->email
                ];
            case Status::ACTIVE:
                return [
                    'title' => 'Activated',
                    'status' => 'Temp',
                ];
        }

        return [];
    }

    /**
     * @param Status $status
     * @param  Request $request
     * @return Response
     * @throws \Twig\Error\Error
     */
    private function showLoggedInHome($status, Request $request)
    {
        $html = $this->createHTMLFromTemplate(
            $this->templating,
            'pages/' . $this->pageMap[(int)$status],
            $this->getParams($status)
        );
        return $this->respond($request, $html);
    }

    /**
     * @param  Request $request
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function main(Request $request): Response
    {

        if ($this->session->isLoggedIn())
            return $this->showLoggedInHome($this->session->getLoginDetails()->status, $request);
        //Need to manage unauthorized post requests sent to this

        return $this->showLoggedOutHome($request);
    }


}