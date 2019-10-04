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
use Skletter\Factory\CookieFactory;
use Skletter\Model\Mediator\AccountService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Login extends AbstractView
{

    /**
     * @var Environment $twig
     */
    private $twig;
    private $account;

    public function __construct(Environment $twig, AccountService $account)
    {
        $this->twig = $twig;
        $this->account = $account;
    }


    /**
     * Redirect to correct location on success otherwise show error messages
     *
     * @param  Request $request
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function attemptLogin(Request $request): Response
    {
        if ($this->loginManager->isLoggedIn()) {
            $cookie = CookieFactory::createSignedCookie($this->loginManager->getCookieIdentity(), 'uid');
            $response = $this->sendSuccessResponse($request, ['status' => 'success'], $_ENV['base_url']);
            $response->headers->setCookie($cookie);

            return $response;
        }
        return $this->sendFailureResponse(
            $request, $this->twig, ['status' => 'failed',
            'error' => $this->state->getError()], 'pages/login_prompt.twig'
        );
    }

    /**
     * @param  Request $request
     * @param  string $method
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