<?php


namespace app\commands;


use app\daemon\RabbitMQQ;

class SendController extends \yii\console\Controller
{

    public function actionIndex()
    {
        $q = new RabbitMQQ('localhost', 5672, 'guest', 'guest');
        $q->send('test', 'Hello JS');
        $q->send('test', 'Hello PHP');
        $q->send('test', 'Hello JS PHP');
        $q->send('test', 'Hello JS PHP');
    }

}