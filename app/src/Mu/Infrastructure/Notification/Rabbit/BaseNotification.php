<?php

namespace Mu\Infrastructure\Notification\Rabbit;

use Mu\Infrastructure\Notification\Notification;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

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
        } catch (\Exception $e) {
            throw new \RuntimeException(
                sprintf('Invalid channel %s', $this->queue)
            );
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
