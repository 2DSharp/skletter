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
use Predis\Client;
use Skletter\Component\FallbackExceptionHandler;
use Skletter\Component\RedisSessionHandler;
use Skletter\Contract\Factory\MapperFactoryInterface;
use Skletter\Contract\Factory\QueryObjectFactoryInterface;
use Skletter\Contract\Repository\IdentityRepositoryInterface;
use Skletter\Factory\MapperFactory;
use Skletter\Factory\QueryObjectFactory;
use Skletter\Model\DTO\LoginState;
use Skletter\Model\DTO\RegistrationState;
use Skletter\Model\Repository\IdentityRepository;
use Skletter\Model\Service\LoginManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;
use function Skletter\Factory\buildLazyLoader;
use function Skletter\Factory\buildPDO;
use function Skletter\Factory\buildPredis;
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

$injector->delegate(Environment::class, getLazyLoadingTwigFactory($lazyloader, $templatesDir, $templatesCacheDir));
$injector->share(Environment::class);

$injector->define(FallbackExceptionHandler::class,
    [':logConfig' => ['LOG_FILE' => __DIR__ . '/../app/logs/error.log']]);

$injector->alias(SessionInterface::class, RedisSessionHandler::class);
$injector->alias(QueryObjectFactoryInterface::class, QueryObjectFactory::class);
$injector->alias(MapperFactoryInterface::class, MapperFactory::class);
$injector->alias(IdentityRepositoryInterface::class, IdentityRepository::class);
$injector->share(LoginState::class);
$injector->share(LoginManager::class);
$injector->share(RegistrationState::class);

$injector->delegate(\PDO::class, buildPDO());
$injector->delegate(Client::class, buildPredis());


return $injector;


