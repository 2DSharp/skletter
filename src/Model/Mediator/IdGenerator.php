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


use Skletter\Model\RemoteService\Uniqid\UniqidInterfaceClient;

class IdGenerator
{
    /**
     * @var UniqidInterfaceClient
     */
    private UniqidInterfaceClient $client;

    public function __construct(UniqidInterfaceClient $client)
    {
        $this->client = $client;
    }

    public function getId(): string
    {
        return $this->client->getNewId();
    }
}