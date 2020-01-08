<?php declare(strict_types = 1);
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Skletter;
use Auryn\Injector;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Predis\Client;
use Skletter\Component\FallbackExceptionHandler;
use Skletter\Component\RedisSessionHandler;
use Skletter\Component\TransportCollector;
use Skletter\Contract\Factory\MapperFactoryInterface;
use Skletter\Contract\Factory\QueryObjectFactoryInterface;
use Skletter\Factory\MapperFactory;
use Skletter\Factory\QueryObjectFactory;
use Skletter\Model\RemoteService\Romeo\RomeoClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Thrift\Transport\TFramedTransport;
use Twig;
use function Skletter\Factory\buildLazyLoader;
use function Skletter\Factory\buildPredis;
use function Skletter\Factory\buildRabbitMQ;
use function Skletter\Factory\buildRPCConnections;
use function Skletter\Factory\getLazyLoadingPDO;
use function Skletter\Factory\getLazyLoadingTwigFactory;
use function Skletter\Factory\getRequestFactory;

$injector = new Injector;
/**
 * Dependencies go here
 * Add factories by delegating functions to their ctors
 */
$injector->delegate(Request::class, getRequestFactory());
$lazyloader = buildLazyLoader(__DIR__ . '/../app/cache/proxies');
$templatesDir = __DIR__ . '/../templates';
$templatesCacheDir = __DIR__ . '/../app/cache/templates';
$injector->delegate(Twig\Environment::class, getLazyLoadingTwigFactory($lazyloader, $templatesDir, $templatesCacheDir));


$injector->delegate(\PDO::class, getLazyLoadingPDO($lazyloader));
$injector->delegate(Client::class, buildPredis());
$injector->delegate(AMQPStreamConnection::class, buildRabbitMQ());
$injector->share(Twig\Environment::class);
$injector->share(TFramedTransport::class);
$injector->share(SessionInterface::class);
$injector->define(
    FallbackExceptionHandler::class,
    [':logConfig' => ['LOG_FILE' => __DIR__ . '/../app/logs/error.log']]
);
$injector->share(TransportCollector::class);
$collector = $injector->make(TransportCollector::class);

buildRPCConnections($injector, $collector);
$injector->share(RomeoClient::class);


$injector->alias(SessionInterface::class, RedisSessionHandler::class);
$injector->alias(QueryObjectFactoryInterface::class, QueryObjectFactory::class);
$injector->alias(MapperFactoryInterface::class, MapperFactory::class);
return $injector;