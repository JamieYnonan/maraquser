<?php

namespace Mu\Infrastructure\Persistence\Doctrine;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Doctrine\ORM\Tools\Setup;
use Mu\Infrastructure\Persistence\Doctrine\Listener\Update;
use Mu\Infrastructure\Persistence\PersistenceException;
use Throwable;

final class EntityManagerFactory
{
    public function build()
    {
        try {
            ExtraTypes::register();
            $eventManager = new EventManager();
            $eventManager->addEventListener([Events::preUpdate], new Update());

            return EntityManager::create(
                [
                    'driver' => getenv('DB_DRIVER'),
                    'host' => getenv('DB_HOST'),
                    'dbname' => getenv('DB_NAME'),
                    'user' => getenv('DB_USER'),
                    'password' => getenv('DB_PASSWORD'),
                    'charset' => getenv('DB_CHARSET')
                ],
                Setup::createYAMLMetadataConfiguration(
                    [
                        __DIR__.'/Mapping/Permission',
                        __DIR__.'/Mapping/Role',
                        __DIR__.'/Mapping/User'
                    ],
                    getenv('APP_DEBUG')
                ),
                $eventManager
            );
        } catch (Throwable $e) {
            throw PersistenceException::byException($e);
        }
    }
}
