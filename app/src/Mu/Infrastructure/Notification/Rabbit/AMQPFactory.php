<?php

namespace Mu\Infrastructure\Notification\Rabbit;

use PhpAmqpLib\Connection\AMQPStreamConnection;

final class AMQPFactory
{
    public static function build(): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            getenv('RABBIT_HOST'),
            getenv('RABBIT_PORT'),
            getenv('RABBIT_USER'),
            getenv('RABBIT_PASSWORD'),
            getenv('RABBIT_VHOST')
        );
    }
}
