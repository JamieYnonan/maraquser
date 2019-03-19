<?php

namespace Mu\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

final class EntityManagerFactory
{
    public function build(array $conn, bool $debug)
    {
        ExtraTypes::register();

        return EntityManager::create(
            $conn,
            Setup::createYAMLMetadataConfiguration(
                [
                    __DIR__ . '/Mapping/Permission',
                    __DIR__ . '/Mapping/Role',
                    __DIR__ . '/Mapping/User'
                ],
                $debug
            )
        );
    }
}
