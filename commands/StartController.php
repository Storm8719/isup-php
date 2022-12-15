<?php


namespace app\commands;


use app\daemon\MainDaemon;
use yii\console\ExitCode;

class StartController extends \yii\console\Controller
{

    public $rollingCurl;
    public $rollingCurlForImages;
    public $imagesResults;

    public function actionIndex($message = 'hello world')
    {
        $daemon = new MainDaemon();
        $daemon->start();
        return ExitCode::OK;
    }

}