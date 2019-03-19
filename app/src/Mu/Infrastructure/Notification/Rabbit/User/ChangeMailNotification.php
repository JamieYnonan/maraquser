<?php

namespace Mu\Infrastructure\Notification\Rabbit\User;

use Mu\Infrastructure\Notification\Rabbit\BaseNotification;

final class ChangeMailNotification extends BaseNotification
{
    protected $queue = 'change_email';
}
