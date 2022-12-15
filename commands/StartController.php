<?php


namespace app\commands;


use app\daemon\MainDaemon;
use app\models\HtmlParser;
use app\models\Sites;
use yii\console\ExitCode;
use app\models\RollingCurlCustom;

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