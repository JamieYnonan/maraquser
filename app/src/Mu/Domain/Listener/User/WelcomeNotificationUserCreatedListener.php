<?php

namespace Mu\Domain\Listener\User;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use Mu\Domain\Model\User\UserService;
use Mu\Infrastructure\Notification\Notification;
use Symfony\Component\Serializer\Serializer;

final class WelcomeNotificationUserCreatedListener extends AbstractListener
{
    private $userService;
    private $userNotification;
    private $serializer;

    public function __construct(
        UserService $userService,
        Notification $notification,
        Serializer $serializer
    ) {
        $this->userService = $userService;
        $this->userNotification = $notification;
        $this->serializer = $serializer;
    }

    public function handle(EventInterface $event): void
    {
        $user = $this->userService->byIdOrFail($event->userId());

        $this->userNotification->publish(
            $this->serializer->serialize(
                $user,
                'json',
                ['groups' => ['notification']]
            )
        );
    }
}
