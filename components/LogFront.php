<?php


namespace app\components;


class LogFront extends \yii\base\Component
{
    public $logCache = '';

    public function log($content){
        $this->logCache .= $content;
    }

    public function getLogs(){
        return $this->logCache;
    }
}