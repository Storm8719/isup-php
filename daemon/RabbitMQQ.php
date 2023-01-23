<?php


namespace app\daemon;


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQQ
{
    private $connection;
    private $channel;

    public function __construct($host, $port, $user, $password)
    {
        try {
            $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
        } catch (\Exception $e) {
            var_dump($e);
        }
        $this->channel = $this->connection->channel();
    }

    public function send($toQueueName, $message){
        $this->channel->queue_declare($toQueueName, false, false, false, false);
        $msg = new AMQPMessage(json_encode($message));
        $this->channel->basic_publish($msg, '', $toQueueName);
    }

    public function subscribeOnMessages($messagesToListenQueueName, $callback){
        $this->channel->queue_declare($messagesToListenQueueName, false, false, false, false);

        $callbackDecode = function ($msg) use ($callback){
            $callback(json_decode($msg->body));
        };

        $this->channel->basic_consume($messagesToListenQueueName, '', false, true, false, false, $callbackDecode);

        while ($this->channel->is_open()) {
            $this->channel->wait();
        }
    }

    public function stop(){
        $this->channel->close();
        try {
            $this->connection->close();
        } catch (\Exception $e) {
            echo $e;
        }
    }
}