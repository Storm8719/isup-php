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



}