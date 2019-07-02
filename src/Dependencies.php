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
use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

$injector = new Injector;
/**
 * Dependencies go here
 * Add factories by delegating functions to their ctors
 */
$requestFactory = function() {
    $obj = Request::createFromGlobals();
    return $obj;
};

$injector->delegate(Request::class, $requestFactory);


$config = new \ProxyManager\Configuration();
$config->setProxiesTargetDir(__DIR__ . '/../app/cache/');

spl_autoload_register($config->getProxyAutoloader());

$lazyloader = new LazyLoadingValueHolderFactory($config);


$twigBuilderFactory = function() use($lazyloader)  {
    $factory = $lazyloader;
    $initializer = function (& $wrappedObject, \ProxyManager\Proxy\LazyLoadingInterface $proxy, $method, array $parameters, & $initializer) {
        $initializer   = null; // disable initialization
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
        $twig = new Environment($loader, [
            'cache' => __DIR__.'/../app/cache/templates',
        ]);
        $wrappedObject = $twig;
        return true;
    };
    return $factory->createProxy(Environment::class, $initializer);
};
$injector->delegate(Environment::class, $twigBuilderFactory);
$injector->share(Environment::class);

return $injector;
