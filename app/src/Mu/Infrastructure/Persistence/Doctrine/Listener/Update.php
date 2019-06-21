<?php

namespace Mu\Infrastructure\Persistence\Doctrine\Listener;

use Doctrine\ORM\Event\PreUpdateEventArgs;

final class Update
{
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        $entityReflection = new \ReflectionClass($entity);
        if ($entityReflection->hasProperty('updatedAt')) {
            $property = $entityReflection->getProperty('updatedAt');
            $property->setAccessible(true);
            $property->setValue($entity, new \DateTime());
            $property->setAccessible(false);
        }
    }
}

