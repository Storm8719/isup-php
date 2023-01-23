<?php


namespace app\commands;

use app\daemon\RabbitMQQ;


class GetController extends \yii\console\Controller
{

    public function actionIndex()
    {
        $q = new RabbitMQQ('localhost', 5672, 'guest', 'guest');
        $q->subscribeOnMessages('check-and-screen-results', function($msg){
            var_dump($msg);
        });
    }

}