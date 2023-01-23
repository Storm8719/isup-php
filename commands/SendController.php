<?php


namespace app\commands;


use app\daemon\WebsiteCheckerService;
use app\models\Sites;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;
use yii\console\ExitCode;

class SendController extends \yii\console\Controller
{

    public function actionIndex($message = 'hello world')
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $queueName = 'test';
        $channel->queue_declare($queueName, false, false, false, false);
        $msg = new AMQPMessage(json_encode('Hello World!'));
        $channel->basic_publish($msg, '', $queueName);
        echo " [x] Sent 'Hello World!'\n";
        $channel->close();
        $connection->close();
    }

}