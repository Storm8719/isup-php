<?php


namespace app\controllers;


use app\models\Sites;
use Yii;
use yii\helpers\HtmlPurifier;

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

        if(!filter_var('https://'.$site, FILTER_VALIDATE_URL, [FILTER_FLAG_HOST_REQUIRED])){
            return $this->render('incorrect_input', ['siteName' => $site]);
        }

        $websiteModel = Sites::findOne(['url' => $site]);

        if(!$websiteModel){
            (new Sites())->createWebsite($site);
            //TODO add logic for checking new website immediately and return page with result for user
        }

        Yii::$app->view->title = HtmlPurifier::process("Сайт $site работает сегодня?");
        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => HtmlPurifier::process("Узнайте, доступен ли сегодня сайт $site в России?")
        ]);
        return $this->render('site', ['siteName' => $site]);
    }

    public function actionGetWebsitesList(){
        $sites = Sites::find()->select(['id', 'url'])->asArray()->all();
        return $this->asJson($sites);
    }

    public function actionRandom(){
        (new Sites())->createRandomWebsite();
        die("done new random website");
    }


}