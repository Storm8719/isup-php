<?php


namespace app\commands;


use app\daemon\RabbitMQQ;

class SendController extends \yii\console\Controller
{

    public function actionIndex()
    {
        $q = new RabbitMQQ('localhost', 5672, 'guest', 'guest');
        $q->send('check-and-screen', ['id' => 5,'url' => 'http://isup/', 'imgName'=> 'www'] );
//        $q->send('test', 'Hello PHP');
//        $q->send('test', 'Hello JS PHP');
//        $q->send('test', 'Hello JS PHP');
    }

}