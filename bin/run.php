<?php

require __DIR__ ."/../vendor/autoload.php";

use RabbitMQ\MyQueueTester;

$queue = new MyQueueTester();

$queue->send('Pim Pam Pum');

$queue->close();