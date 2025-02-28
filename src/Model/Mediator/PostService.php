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


use Skletter\Model\RemoteService\PostOffice\PostDTO;
use Skletter\Model\RemoteService\PostOffice\PostOfficeClient;
use Skletter\Model\ValueObject\Post;

class PostService
{
    /**
     * @var PostOfficeClient
     */
    private PostOfficeClient $client;
    /**
     * @var IdGenerator
     */
    private IdGenerator $generator;

    public function __construct(PostOfficeClient $client, IdGenerator $generator)
    {
        $this->client = $client;
        $this->generator = $generator;
    }

    public function addNewPost(Post $post, int $userId): string
    {
        $dto = new PostDTO();
        $dto->id = $this->generator->getId();
        $dto->userId = $userId;
        $dto->content = $post->content;
        $dto->title = $post->title;

        $this->client->registerPublicPost($dto);
        return $dto->id;
    }
}