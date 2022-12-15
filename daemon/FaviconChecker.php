<?php


namespace app\daemon;


use Yii;

class FaviconChecker extends Checker
{
    protected $observers;
    private $imagesResults;

    public function __construct()
    {
        parent::__construct();
    }

    public function addUrlToCheck(\app\models\Sites $site){
        $faviconsUrlArr = json_decode($site->image_url_options);
        $urlTurn = 1;
        $fetchedCount = count($faviconsUrlArr);
        foreach ($faviconsUrlArr as $url){
            Yii::$app->l->log('Added to FaviconChecker. Site: '.$site->url.' Fav url: '.$url.' Count: '.$fetchedCount.' --> turn '.$urlTurn);
            $this->rc->get($url, null, $this->curlOptions, ['model' => $site, 'turn' => $urlTurn, 'fetchedCount' => $fetchedCount]);
            $urlTurn++;
        }
    }

    public function sendFetchingResults(\RollingCurl\Request $request){

        $websiteModel = $request->getExtraInfo()['model'];
        $turn = $request->getExtraInfo()['turn'];
        $responseInfo = $request->getResponseInfo();
        $turnStatus = ($responseInfo['http_code'] == '200' && preg_match('/.*?image.*?/', $responseInfo['content_type'], $m));

        if($turnStatus && ((!isset($this->imagesResults[$websiteModel->id])) || $this->imagesResults[$websiteModel->id]['turn'] > $turn)){
            $this->imagesResults[$websiteModel->id] = ['turn' => $turn, 'url' => $responseInfo['url']];
        }

        Yii::$app->l->log('FaviconChecker RESULT given: Site: '.$websiteModel->url.' Fav url: '.$responseInfo['url'].' Count: '.$request->getExtraInfo()['fetchedCount'].' --> turn '.$turn.' status: '.$turnStatus);

        if($request->getExtraInfo()['fetchedCount'] == $turn && isset($this->imagesResults[$websiteModel->id]['url'])){
            Yii::$app->l->log('FaviconChecker sended to observer Favicon check result: Site: '.$websiteModel->url.' Fav url: '.$this->imagesResults[$websiteModel->id]['url']);
            foreach ($this->observers as $observer) {
                $observer->setResults(['type' => 'faviconCheck', 'model'=>$websiteModel, 'urlToSet' => $this->imagesResults[$websiteModel->id]['url']]);
//                $observer->setFaviconCheckResults($websiteModel, $this->imagesResults[$websiteModel->id]['url']);
            }
            unset($this->imagesResults[$websiteModel->id]);
        }

    }
}