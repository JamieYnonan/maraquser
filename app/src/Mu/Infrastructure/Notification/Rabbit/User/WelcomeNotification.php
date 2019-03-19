<?php

namespace Mu\Infrastructure\Notification\Rabbit\User;

use Mu\Infrastructure\Notification\Rabbit\BaseNotification;

final class WelcomeNotification extends BaseNotification
{
    protected $queue = 'email_user_welcome';
}
