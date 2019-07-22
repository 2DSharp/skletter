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
use Skletter\Model\DTO\RegistrationState;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Registration extends AbstractView
{
    /**
     * @var Environment $templating
     */
    private $templating;
    private $state;

    public function __construct(Environment $twig, RegistrationState $state)
    {
        $this->templating = $twig;
        $this->state = $state;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function registerUser(Request $request): Response
    {
        if ($this->state->getStatus() == 'success') {
            return $this->sendSuccessResponse($request, ['status' => 'success'], $_ENV['base_url'] . '/success');
        }
        return $this->sendFailureResponse($request, $this->templating, ['status' => 'failed',
            'error' => $this->state->getError()], 'pages/registration.twig');
    }
    /**
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\Error
     */
    private function displayForm(Request $request): Response
    {
        $html = $this->createHTMLFromTemplate($this->templating, 'pages/registration.twig',
            ['title' => 'Skletter - Registration']);
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