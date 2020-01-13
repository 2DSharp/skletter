<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Mediator;


use Carbon\CarbonInterface;
use Jenssegers\Date\Date;
use Skletter\Model\RemoteService\Timeline\PostAggregate;
use Skletter\Model\RemoteService\Timeline\TimelineClient;
use Skletter\Model\ValueObject\Post;

class TimelineService
{
    /**
     * @var TimelineClient
     */
    private TimelineClient $client;

    public function __construct(TimelineClient $client)
    {
        $this->client = $client;
    }

    public function addToPublicTimeline(int $userId, string $postId)
    {
        $this->client->fanout($postId, $userId);
    }

    public function fetchTimeline(int $userId)
    {
        $postList = $this->client->fetchTimeline($userId);
        $posts = [];

        foreach ($postList as $postDTO) {
            array_push($posts, $this->convertToPost($postDTO));
        }
        return $posts;
    }

    private function convertToPost(PostAggregate $postDTO): Post
    {
        $post = new Post();
        $post->setContent($postDTO->post->content);
        $post->title = $postDTO->post->title;
        $date = new Date($postDTO->post->time);
        $post->createdAt = $date->diffForHumans(Date::now(), CarbonInterface::DIFF_ABSOLUTE, true);
        $post->setComposerName($postDTO->user->name);
        $post->setUsername($postDTO->user->username);
        $post->setId($postDTO->post->id);
        $post->img = $postDTO->user->profilePicture . ".jpg";

        return $post;
    }
}