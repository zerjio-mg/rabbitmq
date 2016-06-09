<?php

namespace RabbitMQ;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class MyQueueTester {

    const RABBITMQ_HOST = '192.168.99.100';
    const RABBITMQ_PORT = 5672;
    const RABBITMQ_USER = 'guest';
    const RABBITMQ_PASSWORD = 'guest';

    const EXCHANGE_NAME = 'MyNiceExchange';

    const QUEUE_NAME = 'MyNiceQueue';
    const QUEUE_PASSIVE = false;
    const QUEUE_DURABLE = true;
    const QUEUE_EXCLUSIVE = false;
    const QUEUE_AUTODELETE = false;
    
    const TOPIC_KEY = 'MyNiceTopic';

    /**
     * @var AMQPConnection
     */
    private $connection;

    /**
     * @var AMQPChannel
     */
    private $channel;

    public function __construct()
    {
        $this->connection = new AMQPConnection(
            self::RABBITMQ_HOST,
            self::RABBITMQ_PORT,
            self::RABBITMQ_USER,
            self::RABBITMQ_PASSWORD
        );

        $this->channel = $this->connection->channel();

        $this->channel->queue_declare(
            self::QUEUE_NAME,
            self::QUEUE_PASSIVE,
            self::QUEUE_DURABLE,
            self::QUEUE_EXCLUSIVE,
            self::QUEUE_AUTODELETE
        );
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();

        $this->channel = null;
        $this->connection = null;
    }

    public function send($message)
    {
        if ($this->channel) {
            $this->channel->basic_publish(
                new AMQPMessage($message),
                self::EXCHANGE_NAME,
                self::TOPIC_KEY
            );
        }
    }
}