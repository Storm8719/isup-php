<?php


namespace app\controllers;


use app\daemon\WebsiteCheckerService;
use app\models\AddSiteForm;
use app\models\Sites;
use Yii;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;

class MainController extends \yii\web\Controller
{

    public $layout = "main";

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

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
        $host = Yii::$app->urlHelper->getUrlHost($site);
        if(!$host)
            throw new NotFoundHttpException('Page not found');

        $websiteModel = Sites::findOne(['url' => $site]);
        if(!$websiteModel){
            Yii::$app->response->statusCode = 404;
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->actionAddSiteHandler(true, $site);
        }

        return $this->render('site', ['websiteModel' => $websiteModel]);
    }


    public function actionAddSite(){
        return $this->actionAddSiteHandler();
    }

    private function actionAddSiteHandler($from_404 = null, $siteUrl = null){
        $formModel = new AddSiteForm();
        if($formModel->load(Yii::$app->request->post()) && $formModel->addSite()){
            $websiteModel = $formModel->getSiteModel();
            $checker = new WebsiteCheckerService();
            $checker->checkAndSaveOneWebsite($websiteModel);
            $this->redirect(Url::toRoute(['main/site', 'site' => $websiteModel->url]));
        }

        if($from_404 && $siteUrl){
            $formModel->url = $siteUrl;
        }


        return $this->render('add_website_form', [
            'model' => $formModel,
            'from_404' => $from_404
        ]);
    }

    public function actionDelay(){
//        sleep(3);

        $websiteModel = new Sites();
        $websiteModel->setUrl('php.net');
        $checker = new WebsiteCheckerService();
        $checker->checkAndSaveOneWebsite($websiteModel);
        $this->redirect(Url::toRoute(['main/site', 'site' => $websiteModel->url]));
        return $this->actionIndex();
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