<?php


namespace app\controllers;


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
        return $this->render('index');
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



}