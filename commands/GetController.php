<?php


namespace app\commands;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class GetController extends \yii\console\Controller
{

    public function actionIndex($message = 'hello world')
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $queueName = 'test';
        $channel->queue_declare($queueName, false, false, false, false);

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };

        $channel->basic_consume($queueName, '', false, true, false, false, $callback);

        while ($channel->is_open()) {
            $channel->wait();
        }
    }

}