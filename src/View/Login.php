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
use Skletter\Model\Service\LoginManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Login extends AbstractView
{
    /**
     * Shared LoginManager service to retrieve current login state
     * @var LoginManager $loginManager
     */
    private $loginManager;
    /**
     * @var Environment $twig
     */
    private $twig;

    public function __construct(LoginManager $loginManager, Environment $twig)
    {
        $this->loginManager = $loginManager;
        $this->twig = $twig;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function attemptLogin(Request $request): Response
    {
        if ($this->loginManager->isLoggedIn())
            return new RedirectResponse($_ENV['base_url'] . '/success');
        else {
            if ($request->isXmlHttpRequest())
                return new JsonResponse($this->loginManager->getErrors());
            else
                return $this->respond($request, $this->createHTMLFromTemplate($this->twig,
                    'login_prompt.twig',
                    ['status' => 'failed', 'errors' => $this->loginManager->getErrors()]));
        }
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