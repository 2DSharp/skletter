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
use Skletter\Model\DTO\LoginState;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Environment;

class Login extends AbstractView
{
    /**
     * Shared DTO to retrieve current login state
     * @var LoginState $state
     */
    private $state;
    /**
     * @var Environment $twig
     */
    private $twig;
    private $session;

    public function __construct(LoginState $state, Environment $twig, Session $session)
    {
        $this->twig = $twig;
        $this->state = $state;
        $this->session = $session;
    }


    /**
     * Redirect to correct location on success otherwise show error messages
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function attemptLogin(Request $request): Response
    {
        if ($this->state->isLoggedIn()) {
            return $this->sendSuccessResponse($request, ['status' => 'success'], $_ENV['base_url'] . '/success');
        }
        return $this->sendFailureResponse($request, $this->twig, ['status' => 'failed',
            'error' => $this->state->getError()], 'pages/login_prompt.twig');
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