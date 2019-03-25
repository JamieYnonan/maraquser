<?php

namespace Mu\Infrastructure\CommandBus;

use Doctrine\ORM\EntityManager;
use League\Event\Emitter;
use League\Tactician\CommandBus;
use League\Tactician\Doctrine\ORM\TransactionMiddleware;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Plugins\LockingMiddleware;
use Mu\Application\Permission\CreatePermissionCommand;
use Mu\Application\Permission\CreatePermissionHandler;
use Mu\Application\Permission\UpdatePermissionCommand;
use Mu\Application\Permission\UpdatePermissionHandler;
use Mu\Application\Role\AddPermissionCommand;
use Mu\Application\Role\AddPermissionHandler;
use Mu\Application\Role\CreateRoleCommand;
use Mu\Application\Role\CreateRoleHandler;
use Mu\Application\Role\RemovePermissionCommand;
use Mu\Application\Role\RemovePermissionHandler;
use Mu\Application\Role\UpdateRoleCommand;
use Mu\Application\Role\UpdateRoleHandler;
use Mu\Application\User\ChangeEmailCommand;
use Mu\Application\User\ChangeEmailHandler;
use Mu\Application\User\ChangeRoleCommand;
use Mu\Application\User\ChangeRoleHandler;
use Mu\Application\User\CreateUserCommand;
use Mu\Application\User\CreateUserHandler;
use Mu\Application\User\DeleteUserCommand;
use Mu\Application\User\DeleteUserHandler;
use Mu\Domain\Listener\User\EmailChangedNotificationListener;
use Mu\Domain\Listener\User\WelcomeNotificationUserCreatedListener;
use Mu\Domain\Model\User\UserChangedEmailEvent;
use Mu\Domain\Model\User\UserCreatedEvent;
use Mu\Domain\Model\User\UserDeletedEvent;
use Mu\Domain\Model\User\UserUpdatedEvent;
use Mu\Infrastructure\Domain\Event\DomainEventListenerMiddleware;
use Psr\Container\ContainerInterface;

final class CommandBusFactory
{
    public static function build(ContainerInterface $container): CommandBus
    {
        return new CommandBus(
            [
                new LockingMiddleware(),
                new TransactionMiddleware(
                    $container->get(EntityManager::class)
                ),
                new DomainEventListenerMiddleware(
                    new Emitter(),
                    [
                        UserCreatedEvent::EVENT_NAME => [
                            $container->get(
                                WelcomeNotificationUserCreatedListener::class
                            )
                        ],
                        UserUpdatedEvent::EVENT_NAME => [],
                        UserDeletedEvent::EVENT_NAME => [],
                        UserChangedEmailEvent::EVENT_NAME => [
                            $container->get(
                                EmailChangedNotificationListener::class
                            )
                        ]
                    ]
                ),
                new CommandHandlerMiddleware(
                    new ClassNameExtractor(),
                    self::commandHanlderInMemoryLocation($container),
                    new HandleInflector()
                )
            ]
        );
    }

    private static function commandHanlderInMemoryLocation(
        ContainerInterface$container
    ): InMemoryLocator {
        return new InMemoryLocator(
            array_merge(
                self::commandHandlerPermission($container),
                self::commandHandlerRole($container),
                self::commandHandlerUser($container)
            )
        );
    }
    
    private static function commandHandlerPermission(
        ContainerInterface $container
    ): array {
        return [
            CreatePermissionCommand::class => $container->get(
                CreatePermissionHandler::class
            ),
            UpdatePermissionCommand::class => $container->get(
                UpdatePermissionHandler::class
            )
        ];
    }
    
    private static function commandHandlerRole(
        ContainerInterface $container
    ): array {
        return [
            CreateRoleCommand::class => $container->get(
                CreateRoleHandler::class
            ),
            UpdateRoleCommand::class => $container->get(
                UpdateRoleHandler::class
            ),
            AddPermissionCommand::class => $container->get(
                AddPermissionHandler::class
            ),
            RemovePermissionCommand::class => $container->get(
                RemovePermissionHandler::class
            )
        ];
    }
    
    private static function commandHandlerUser(
        ContainerInterface $container
    ): array {
        return [
            CreateUserCommand::class => $container->get(
                CreateUserHandler::class
            ),
            ChangeEmailCommand::class => $container->get(
                ChangeEmailHandler::class
            ),
            ChangeRoleCommand::class => $container->get(
                ChangeRoleHandler::class
            ),
            DeleteUserCommand::class => $container->get(
                DeleteUserHandler::class
            )
        ];
    }
}
