<?php


namespace app\commands;


use app\models\HtmlParser;
use app\models\Sites;
use yii\console\ExitCode;
use app\models\RollingCurlCustom;

class StartController extends \yii\console\Controller
{

    public $rollingCurl;
    public $rollingCurlForImages;
    public $imagesResults;

    public function actionIndex($message = 'hello world')
    {
        $this->initRollingCurl();
        $this->startDaemon();
        return ExitCode::OK;
    }

    private function initRollingCurl(){
        $this->rollingCurl = new RollingCurlCustom();
        $this->rollingCurl->setSimultaneousLimit(5);
        $this->rollingCurl->setCallback(function(\RollingCurl\Request $request, RollingCurlCustom $rollingCurl){
            $this->setFetchingResults($request);
            $rollingCurl->clearCompleted();
//            $rollingCurl->prunePendingRequestQueue();
        });
        $this->rollingCurlForImages = new RollingCurlCustom();
        $this->rollingCurlForImages->setSimultaneousLimit(8);
        $this->rollingCurlForImages->setCallback(function(\RollingCurl\Request $request, RollingCurlCustom $rollingCurl){
            $this->setFetchingResultsForImages($request);
            $rollingCurl->clearCompleted();
//            $rollingCurl->prunePendingRequestQueue();
        });

    }

    public function startDaemon(){

        $sites = Sites::find()->where(['status' => 0])->all();

        $start = microtime(true);
        echo "Fetching..." . PHP_EOL;

        foreach ($sites as $site){
            echo 'Updating '.$site->url.PHP_EOL;
            $url = ($site->scheme ? $site->scheme.'://'.$site->url : 'https://'.$site->url);
            $this->rollingCurl->get($url, null, [CURLOPT_NOBODY => false, CURLOPT_TIMEOUT => 8], ['model' => $site]);
        }

//        try {
            $this->rollingCurl->execute();
            $this->rollingCurlForImages->execute();

//        } catch (\Exception $e) {
//            echo "error executing";
//        }
        echo "...done in " . (microtime(true) - $start) . PHP_EOL;
    }




    private function setFetchingResults(\RollingCurl\Request $request){

        $responseInfo = $request->getResponseInfo();
        $websiteModel = $request->getExtraInfo()['model'];
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
            $this->fetchFavicons($websiteModel, $faviconsUrlArr);
        }
//        echo "Updated ".$websiteModel->url . PHP_EOL;
    }

    public function fetchFavicons(\app\models\Sites $websiteModel, $faviconsUrlArr = null){

        if(!$faviconsUrlArr)
            $faviconsUrlArr = json_decode($websiteModel->image_url_options);

        $urlTurn = 1;
        $fetchedCount = count($faviconsUrlArr);
        foreach ($faviconsUrlArr as $url){
            $this->rollingCurlForImages->get($url, null, [CURLOPT_NOBODY => true, CURLOPT_TIMEOUT => 4], ['model' => $websiteModel, 'turn' => $urlTurn, 'fetchedCount' => $fetchedCount]);
            $urlTurn++;
        }
    }

    private function setFetchingResultsForImages(\RollingCurl\Request $request){
        $websiteModel = $request->getExtraInfo()['model'];
        $turn = $request->getExtraInfo()['turn'];
        $fetchedCount = $request->getExtraInfo()['fetchedCount'];
        $responseInfo = $request->getResponseInfo();

        $turnStatus = ($responseInfo['http_code'] == '200' && preg_match('/.*?image.*?/', $responseInfo['content_type'], $m));

        if($turnStatus && ((!isset($this->imagesResults[$websiteModel->id])) || $this->imagesResults[$websiteModel->id]['turn'] > $turn)){
            $this->imagesResults[$websiteModel->id] = ['turn' => $turn, 'url' => $responseInfo['url']];
        }

        if($fetchedCount == $turn && isset($this->imagesResults[$websiteModel->id]['url'])){
            $websiteModel->image_url = $this->imagesResults[$websiteModel->id]['url'];
            $websiteModel->is_image_setted = 1;
            $websiteModel->save();
        }

        echo $responseInfo['url'].' WS id = '.$websiteModel->id." Total fetched = ".$fetchedCount." --> this turn = ".$turn." status ".$turnStatus.PHP_EOL;
    }

}