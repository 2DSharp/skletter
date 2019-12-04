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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccountSetupWizard extends AbstractView
{
    /**
     * @var SessionManager
     */
    private SessionManager $session;

    public function __construct(SessionManager $sessionManager)
    {
        $this->session = $sessionManager;
    }

    private $stepData = [
        '1' => [
            'title' => 'Upload a profile picture',
            'description' => 'A profile picture is associated with your identity making you unique.',
        ]
    ];

    public function displayStepContent(Request $request)
    {
        return new JsonResponse($this->stepData[$request->query->get('step')]);
    }
}