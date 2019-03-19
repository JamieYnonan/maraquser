<?php

namespace Mu\Infrastructure\Api\Custom\Routes;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

class RoutingFactory
{
    public static function build(): RouteCollection
    {
        $fileLocator = new FileLocator(array(__DIR__));
        $loader = new YamlFileLoader($fileLocator);
        return $loader->load('routes.yaml');
    }
}
