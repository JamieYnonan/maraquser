<?php

use Mu\Infrastructure\Api\Custom\HttpKernel\ControllerResolver;
use Mu\Infrastructure\Api\Custom\HttpKernel\ArgumentResolver;
use Mu\Infrastructure\DependencyInjection\DependencyInjectionFactory;
use Mu\Infrastructure\Api\Custom\Routes\RoutingFactory;
use Mu\Infrastructure\Api\Custom\Application;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require __DIR__.'/../../../../../../vendor/autoload.php';

if (getenv('APP_DEBUG')) {
    Symfony\Component\Debug\Debug::enable();
}

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../../../../../../.env');

$container = DependencyInjectionFactory::build();

if ($container->hasParameter('app.timezone')) {
    date_default_timezone_set($container->getParameter('app.timezone'));
}

$request = Request::createFromGlobals();
$routing = RoutingFactory::build();

$urlMatcher = new UrlMatcher(
    $routing,
    (new RequestContext)->fromRequest($request)
);

$app = new Application(
    new ControllerResolver($urlMatcher, $container),
    new ArgumentResolver($urlMatcher)
);

$response = $app->handle($request);

$response->send();
