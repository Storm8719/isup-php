<?php


namespace app\commands;


use app\daemon\RabbitMQQ;
use stdClass;

class SendController extends \yii\console\Controller
{

    public function actionIndex()
    {

        $obj = new stdClass();
//        $obj->id = "47a0facd-1187-4945-b546-be9281281396";
        $obj->event = "screenshots.make";
        $obj->data = new stdClass();
        $obj->data->user = "Oleg S";
        $obj->groups = ["screenshots"];
//        $obj->broadcast = false;
//        $obj->meta = new stdClass();
//        $obj->level = 1;
//        $obj->tracing = null;
//        $obj->requestID = "5f0d4f05-a9fd-4fa8-855c-a9ee8d88ad00";
//        $obj->caller = null;
//        $obj->needAck = null;
        $obj->ver = "4";
//        $obj->sender = "desktop-pv134n5-1808";


        $q = new RabbitMQQ('localhost', 5672, 'guest', 'guest');
        $q->send('MOL.EVENT.node2', $obj);


//        var_dump($obj);

//        while(true){
//            $q->send('check-and-screen-2', ['id' => 5,'url' => 'http://isup/', 'imgName'=> 'www'] );
//            sleep(3);
//            echo 'Sended ...'.PHP_EOL;
//        }

//        $q->subscribeOnMessages('check-and-screen-results', function($msg){
//            var_dump($msg);
//        });
//        $q->send('test', 'Hello PHP');
//        $q->send('test', 'Hello JS PHP');
//        $q->send('test', 'Hello JS PHP');
    }

}