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
        $this->init($host, $port, $user, $password);
    }

    protected function init($host, $port, $user, $password){
        try {
            $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
            $this->channel = $this->connection->channel();
        } catch (\Exception $e) {
            \Yii::$app->l->log("Connection to rabbit failed. Try to reconnect in 1s...");
            sleep(1);
            $this->init($host, $port, $user, $password);
        }
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