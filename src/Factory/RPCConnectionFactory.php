<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Factory;

use Auryn\Injector;
use Skletter\Component\TransportCollector;
use Skletter\Model\RemoteService\PhotoBooth\PhotoBoothClient;
use Skletter\Model\RemoteService\PostOffice\PostOfficeClient;
use Skletter\Model\RemoteService\Romeo\RomeoClient;
use Skletter\Model\RemoteService\Search\SearchClient;
use Skletter\Model\RemoteService\SocialGraph\SocialGraphClient;
use Skletter\Model\RemoteService\Timeline\TimelineClient;
use Skletter\Model\RemoteService\Uniqid\UniqidInterfaceClient;

function buildRPCConnections(Injector &$injector, TransportCollector &$collector)
{
    $injector->delegate(RomeoClient::class, function () use (&$collector) {
        $protocol = buildBinaryProtocol("localhost", 9090, $collector);
        return new RomeoClient($protocol);
    });

    $injector->delegate(SearchClient::class, function () use (&$collector) {
        $protocol = buildBinaryProtocol("localhost", 9091, $collector);
        return new SearchClient($protocol);
    });

    $injector->delegate(PhotoBoothClient::class, function () use (&$collector) {
        $protocol = buildBinaryProtocol("localhost", 9092, $collector);
        return new PhotoBoothClient($protocol);
    });

    $injector->delegate(TimelineClient::class, function () use (&$collector) {
        $protocol = buildBinaryProtocol("localhost", 9093, $collector);
        return new TimelineClient($protocol);
    });
    $injector->delegate(SocialGraphClient::class, function () use (&$collector) {
        $protocol = buildBinaryProtocol("localhost", 9094, $collector);
        return new SocialGraphClient($protocol);
    });
    $injector->delegate(PostOfficeClient::class, function () use (&$collector) {
        $protocol = buildBinaryProtocol("localhost", 9095, $collector);
        return new PostOfficeClient($protocol);
    });

    $injector->delegate(UniqidInterfaceClient::class, function () use (&$collector) {
        $protocol = buildBinaryProtocol("localhost", 9096, $collector);
        return new UniqidInterfaceClient($protocol);
    });
}