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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Registration extends AbstractView
{
    /**
     * @var Environment $templating
     */
    private $templating;
    private $session;

    public function __construct(Environment $twig, SessionManager $session)
    {
        $this->templating = $twig;
        $this->session = $session;
    }

    /**
     * @param  Request $request
     * @param array $dto
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function registerUser(Request $request, array $dto): Response
    {
        if ($dto['success']) {
            return $this->sendSuccessResponse(
                $request,
                ['status' => 'success', 'result' => $this->templating->render(
                    'pieces/contact_verification_prompt.twig',
                    ['email' => $this->session->getLoginDetails()->email]
                )],
                $_ENV['base_url'] . '/register'
            );
        }
        $postData = [
            'name' => $request->request->get('name'),
            'email' => $request->request->get('email'),
            'username' => $request->request->get('username')
        ];
        return $this->sendFailureResponse(
            $request, $this->templating, ['status' => 'failed',
            'errors' => $dto['errors'], 'post' => $postData], 'pages/registration.twig'
        );
    }
    /**
     * @param  Request $request
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function displayForm(Request $request): Response
    {
        $html = $this->createHTMLFromTemplate(
            $this->templating, 'pages/registration.twig',
            ['title' => 'Skletter - Registration',
                'status' => $this->session->getLoginDetails()->status,
                'email' => $this->session->getLoginDetails()->email]
        );
        return $this->respond($request, $html);
    }


}