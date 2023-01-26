<?php


namespace app\daemon;


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQQ
{
    private $connection;
    private $channel;
//    private $messagesToSendQueue;
    private $connectionCreds;

    public function __construct($host, $port, $user, $password)
    {
//        $this->messagesToSendQueue = new \SplQueue();

        $this->connectionCreds = [
            'host' => $host,
            'port' => $port,
            'user' => $user,
            'password' => $password
            ];

        $this->init();
    }

    protected function init(){

        try {
            $this->connection = new AMQPStreamConnection($this->connectionCreds['host'], $this->connectionCreds['port'], $this->connectionCreds['user'], $this->connectionCreds['password']);
            $this->channel = $this->connection->channel();
        } catch (\Exception $e) {
            \Yii::$app->l->log("Connection to rabbit failed. Try to reconnect in 1s...");
            sleep(1);
            $this->init();
        }
    }

    public function send($toQueueName, $message){
        try {
            $this->channel->queue_declare($toQueueName, false, false, false, false);
        }catch (\Exception $e){
//            $this->messagesToSendQueue->enqueue(["toQueueName" => $toQueueName, "message" => $message]);
            $this->init();
        }

        $msg = new AMQPMessage(json_encode($message));
        $this->channel->basic_publish($msg, '', $toQueueName);

    }

    public function subscribeOnMessages($messagesToListenQueueName, $callback){
        try {
            $this->channel->queue_declare($messagesToListenQueueName, false, false, false, false);
        }catch (\Exception $e){
            $this->init();
        }

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