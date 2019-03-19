<?php

namespace Mu\Infrastructure\Notification;

interface Notification
{
    public function publish(string $message): void;
}
