<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Controller;


use Skletter\Component\Core\Controller;
use Skletter\Model\Mediator\AccountService;
use Skletter\Model\Mediator\PostService;
use Skletter\Model\Mediator\TimelineService;
use Skletter\Model\ValueObject\Post;
use Symfony\Component\HttpFoundation\Request;

class PostLetter implements Controller
{
    use ControllerTrait;
    /**
     * @var TimelineService
     */
    private TimelineService $timelineService;
    /**
     * @var AccountService
     */
    private AccountService $accountService;
    /**
     * @var PostService
     */
    private PostService $postService;

    public function __construct(AccountService $accountService, PostService $postService,
                                TimelineService $timelineService)
    {
        $this->accountService = $accountService;
        $this->postService = $postService;
        $this->timelineService = $timelineService;
    }

    public function post(Request $request)
    {
        $content = $request->request->get("content");
        $title = $request->request->get("title");
        $postVO = new Post();
        $postVO->title = $title;
        $postVO->content = $content;
        $user = $this->accountService->getSessionUser()->getId();
        $postId = $this->postService->addNewPost($postVO, $this->accountService->getSessionUser()->getId());
        $this->timelineService->addToPublicTimeline($user, $postId);
        return ['id' => $postId];
    }
}