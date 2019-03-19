<?php

namespace Mu\Infrastructure\Notification\Rabbit;

use PhpAmqpLib\Connection\AMQPStreamConnection;

final class AMQPFactory
{
    public static function build(array $config): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password'],
            $config['vhost']
        );
    }
}
