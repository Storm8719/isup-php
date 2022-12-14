<?php


namespace app\components;


class Log extends \yii\base\Component
{
    public $logStatus = 1;

    public function log($content){
        if($this->logStatus){
            echo $content.PHP_EOL;
        }
    }
}