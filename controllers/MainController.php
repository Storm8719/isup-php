<?php


namespace app\controllers;


use app\daemon\WebsiteCheckerService;
use app\models\AddSiteForm;
use app\models\Sites;
use Yii;
use yii\helpers\Url;
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
        $sites = Sites::find()->limit(30)->asArray()->all();

        return $this->render('index', ['sites' => $sites]);
    }

    public function actionTimeout($time){

        echo $time;
        sleep(rand(1, 5));
        echo '-----';
        die;
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

        if($formModel->getErrors('url') && $formModel->getErrors('url')[0] == "url_already_exist")
            return $this->redirect(Url::toRoute(['main/site', 'site' => $formModel->url]));

        if($from_404 && $siteUrl)
            $formModel->url = $siteUrl;

        return $this->render('add_website_form', [
            'model' => $formModel,
            'from_404' => $from_404
        ]);
    }

    public function actionGetWebsitesList(){
        $sites = Sites::find()->select(['id', 'url'])->asArray()->all();
        return $this->asJson($sites);
    }


}