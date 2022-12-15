<?php


namespace app\commands;


use app\daemon\WebsiteCheckerService;
use app\models\Sites;
use Yii;
use yii\console\ExitCode;

class StartController extends \yii\console\Controller
{

    public function actionIndex($message = 'hello world')
    {
        $daemon = new WebsiteCheckerService();

        $start = microtime(true);
        Yii::$app->l->log('Service starts');

        $sites = Sites::find()->all();

        $daemon->checkAndSaveWebsites($sites);

        Yii::$app->l->log("...done in " . (microtime(true) - $start));

        return ExitCode::OK;
    }

}