<?php

namespace Mu\Infrastructure\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Throwable;

class DependencyInjectionFactory
{
    public static function build(): ContainerBuilder
    {
        try {
            $container = new ContainerBuilder();
            $loader = new YamlFileLoader(
                $container,
                new FileLocator(__DIR__)
            );
            $loader->load('services.yaml');

            $container->compile();

            return $container;
        } catch (Throwable $e) {
            throw DependencyInjectionException::byException($e);
        }
    }
}
