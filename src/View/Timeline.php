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


use Skletter\Model\Mediator\AccountService;
use Skletter\Model\Mediator\TimelineService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Timeline extends AbstractView
{
    /**
     * @var TimelineService
     */
    private TimelineService $timeline;
    /**
     * @var AccountService
     */
    private AccountService $account;

    public function __construct(TimelineService $timeline, AccountService $accountService)
    {
        $this->timeline = $timeline;
        $this->account = $accountService;
    }

    public function fetchTimeline(Request $request): Response
    {
        return new JsonResponse($this->timeline->fetchTimeline($this->account->getSessionUser()->getId()));
    }
}