<?php

namespace Mu\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Mu\Infrastructure\Persistence\PersistenceException;
use Throwable;

final class EntityManagerFactory
{
    public function build()
    {
        try {
            ExtraTypes::register();

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
                )
            );
        } catch (Throwable $e) {
            throw PersistenceException::byException($e);
        }
    }
}
