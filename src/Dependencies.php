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
use Skletter\Component\EmailQueuer;
use Skletter\Component\FallbackExceptionHandler;
use Skletter\Component\RedisSessionHandler;
use Skletter\Contract\Component\Mailer;
use Skletter\Contract\Factory\MapperFactoryInterface;
use Skletter\Contract\Factory\QueryObjectFactoryInterface;
use Skletter\Factory\MapperFactory;
use Skletter\Factory\QueryObjectFactory;
use Skletter\Model\RemoteService\Romeo\RomeoClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TProtocol;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TTransport;
use Twig;
use function Skletter\Factory\buildLazyLoader;
use function Skletter\Factory\buildPredis;
use function Skletter\Factory\buildRabbitMQ;
use function Skletter\Factory\buildTFramedTransport;
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
$injector->delegate(TFramedTransport::class, buildTFramedTransport($lazyloader, 'localhost', 9090));
$injector->delegate(\PDO::class, getLazyLoadingPDO($lazyloader));
$injector->delegate(Client::class, buildPredis());
$injector->delegate(AMQPStreamConnection::class, buildRabbitMQ());


$injector->share(Twig\Environment::class);
$injector->share(TFramedTransport::class);

$injector->define(
    FallbackExceptionHandler::class,
    [':logConfig' => ['LOG_FILE' => __DIR__ . '/../app/logs/error.log']]
);

$injector->alias(TTransport::class, TFramedTransport::class);

$injector->define(TBinaryProtocol::class, [':trans' => $injector->make(TTransport::class)]);
$injector->alias(TProtocol::class, TBinaryProtocol::class);
$injector->define(RomeoClient::class, [':input' => $injector->make(TProtocol::class)]);
$injector->alias(SessionInterface::class, RedisSessionHandler::class);
$injector->alias(QueryObjectFactoryInterface::class, QueryObjectFactory::class);
$injector->alias(MapperFactoryInterface::class, MapperFactory::class);
$injector->alias(Mailer::class, EmailQueuer::class);


return $injector;


