<?php


namespace app\controllers;


use app\models\Sites;
use Yii;

class MainController extends \yii\web\Controller
{

    public $layout = "main";

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->view->title = "Main Page";
        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Description of the main page...'
        ]);

        $sites = Sites::find()->asArray()->limit(3)->all();

        return $this->render('index', ['sites' => $sites]);
    }

    public function actionSite($site)
    {
        Yii::$app->view->title = $site;
        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Description of the main page...'
        ]);
//        $res = preg_match("/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/", $site);

        return $this->render('site', ['siteName' => $site]);
    }

    public function actionRandom(){
        $site = new Sites();
        $site->url = 'random';
        $site->status = 0;
        $site->created_at = time();
        $site->save();
        die("done new random website");
    }


}