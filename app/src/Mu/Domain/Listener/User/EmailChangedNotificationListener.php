<?php

namespace Mu\Domain\Listener\User;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use Mu\Domain\Model\User\UserService;
use Mu\Infrastructure\Notification\Notification;
use Symfony\Component\Serializer\Serializer;

final class EmailChangedNotificationListener extends AbstractListener
{
    private $userService;
    private $notification;
    private $serializer;

    public function __construct(
        UserService $userService,
        Notification $notification,
        Serializer $serializer
    ) {
        $this->userService = $userService;
        $this->notification = $notification;
        $this->serializer = $serializer;
    }

    public function handle(EventInterface $event): void
    {
        $user = $this->userService->byIdOrFail($event->userId());

        $this->notification->publish(
            $this->serializer->serialize(
                $user,
                'json',
                ['groups' => ['notification']]
            )
        );
    }
}
