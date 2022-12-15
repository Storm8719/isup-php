<?php


namespace app\daemon;


use app\components\HtmlParser;
use app\models\Sites;
use Yii;

class MainDaemon implements CheckerObserver
{
    public $htmlChecker;
    public $faviconChecker;

    public function start(){
        $start = microtime(true);
        Yii::$app->l->log('Daemon starts');
        $this->htmlChecker = new MainChecker();
        $this->htmlChecker->attach($this);
        $this->faviconChecker = new FaviconChecker();
        $this->faviconChecker->attach($this);

        $sites = Sites::find()->where(['status' => 1])->all();
        $this->htmlChecker->addUrlToCheckArr($sites);
        $this->htmlChecker->execute();
        $this->faviconChecker->execute();
        Yii::$app->l->log("Daemon Ends...done in " . (microtime(true) - $start));
    }

    public function setResults($data){
        if ($data['type'] == 'faviconCheck')
        {
            $this->setFaviconCheckResults($data['model'], $data['urlToSet']);
        }
        elseif ($data['type'] == 'mainCheck')
        {
            $this->setHtmlCheckResults($data['request']);
        }
    }


    public function setHtmlCheckResults(\RollingCurl\Request $request){


        $responseInfo = $request->getResponseInfo();
        $websiteModel = $request->getExtraInfo()['model'];
        Yii::$app->l->log('Html Check Result for '.$websiteModel->url.' given');
        $parser = new HtmlParser($request->getResponseText(), $responseInfo['url']);
        $websiteModel->last_http_code = (int) $responseInfo['http_code'];
        $websiteModel->updated_at = time();
        if(!$websiteModel->last_http_code){
            return $websiteModel->save();
        }
        if($responseInfo['redirect_count'])
            $websiteModel->redirect_to = $responseInfo['url'];
        $websiteModel->ttfb = floatval($responseInfo['starttransfer_time']) * 1000; //ms
        $websiteModel->header = $parser->getTitle();
        $websiteModel->description = $parser->getDescription();
        $faviconsUrlArr = $parser->getFaviconUrlCandidatesArray();
        $websiteModel->image_url_options = json_encode($faviconsUrlArr);

        $websiteModel->save();

        if(!empty($faviconsUrlArr)){
            Yii::$app->l->log('Favicon request for '.$websiteModel->url.' sended...');
            $this->faviconChecker->addUrlToCheck($websiteModel);
        }

    }

    public function setFaviconCheckResults(\app\models\Sites $websiteModel, $imageUrlStr){
        Yii::$app->l->log('Favicon result for '.$websiteModel->url.' given: '. $imageUrlStr);
        $websiteModel->image_url = $imageUrlStr;
        $websiteModel->is_image_setted = 1;
        $websiteModel->save();
    }
}