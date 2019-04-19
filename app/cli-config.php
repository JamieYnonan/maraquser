<?php
require_once __DIR__.'/vendor/autoload.php';

use Mu\Infrastructure\DependencyInjection\DependencyInjectionFactory;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$container = DependencyInjectionFactory::build();

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet(
    $container->get(\Doctrine\ORM\EntityManager::class)
);
