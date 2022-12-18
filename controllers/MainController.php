<?php


namespace app\controllers;


use app\daemon\WebsiteCheckerService;
use app\models\AddSiteForm;
use app\models\Sites;
use Yii;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

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
        $host = Yii::$app->urlHelper->getUrlHost($site);
        if(!$host)
            return false;

        $websiteModel = Sites::findOne(['url' => $site]);
        if(!$websiteModel)
            return false;

        Yii::$app->view->title = HtmlPurifier::process("Сайт $site работает сегодня?");
        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => HtmlPurifier::process("Узнайте, доступен ли сегодня сайт $site в России?")
        ]);
        return $this->render('site', ['siteName' => $site]);
    }

    public function actionAddSite(){
        $formModel = new AddSiteForm();
        if($formModel->load(Yii::$app->request->post()) && $formModel->addSite()){
            $websiteModel = $formModel->getSiteModel();
            $checker = new WebsiteCheckerService();
            $checker->checkAndSaveOneWebsite($websiteModel);
            $this->redirect(Url::toRoute(['main/site', 'site' => $websiteModel->url]));
        }
        return $this->render('add_website_form', [
            'model' => $formModel,
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