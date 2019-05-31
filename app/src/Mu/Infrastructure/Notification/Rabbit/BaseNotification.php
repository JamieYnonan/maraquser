<?php

namespace Mu\Infrastructure\Notification\Rabbit;

use Mu\Infrastructure\Notification\Notification;
use Mu\Infrastructure\Notification\NotificationException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;

abstract class BaseNotification implements Notification
{
    private $channel;

    protected $queue;

    public function __construct(AMQPStreamConnection $connection)
    {
        try {
            $this->channel = new AMQPChannel($connection);
            $this->channel->queue_declare(
                $this->queue,
                false,
                true,
                false,
                false
            );
        } catch (Throwable $e) {
            throw NotificationException::byException($e);
        }
    }

    public function publish(string $message): void
    {
        $this->channel->basic_publish(
            new AMQPMessage($message),
            '',
            $this->queue
        );
    }
}
