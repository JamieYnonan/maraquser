<?php
require_once __DIR__.'/vendor/autoload.php';

use Mu\Infrastructure\DependencyInjection\DependencyInjectionFactory;


$container = DependencyInjectionFactory::build();

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet(
    $container->get(\Doctrine\ORM\EntityManager::class)
);
