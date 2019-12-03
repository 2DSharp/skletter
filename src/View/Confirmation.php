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
use Twig\Environment;

class Confirmation extends AbstractView
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param Request $request
     * @param array $dto
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function confirmRegistrationWithToken(Request $request, array $dto)
    {
        if ($dto['success']) {
            return $this->sendSuccessResponse($request, [], '/login?username=' . $dto['username']);
        } else {
            $html = $this->twig->render("pages/staticinfo.twig",
                                        ['message' => $dto['errors']['global']->message,
                                            'title' => 'Confirmation']);
            return $this->respond($request, $html);
        }
    }

    public function confirmRegistrationWithPin(Request $request, array $dto)
    {
        if ($dto['success']) {
            return $this->sendSuccessResponse($request, [], '/?accountSetupWizard=1');
        } else {
            $html = $this->twig->render("pages/staticinfo.twig",
                                        ['message' => $dto['errors']['global']->message,
                                            'title' => 'Confirmation']);
            return $this->respond($request, $html);
        }
    }

}