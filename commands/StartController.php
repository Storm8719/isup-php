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
        $this->rollingCurl->setSimultaneousLimit(7);
        $this->rollingCurl->setCallback(function(\RollingCurl\Request $request, RollingCurlCustom $rollingCurl){
            $this->setFetchingResults($request);
            $rollingCurl->clearCompleted();
//            $rollingCurl->prunePendingRequestQueue();
        });
        $this->rollingCurlForImages = new RollingCurlCustom();
        $this->rollingCurlForImages->setSimultaneousLimit(5);
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
            $this->rollingCurl->get($url, null, [CURLOPT_NOBODY => false], ['model' => $site]);
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
        $websiteModel->ttfb = floatval($responseInfo['starttransfer_time']) * 1000; //ms
        $websiteModel->updated_at = time();
        $websiteModel->header = $parser->getTitle();
        $websiteModel->description = $parser->getDescription();
        $faviconsUrlArr = $parser->getFaviconUrlCandidatesArray();
        $websiteModel->image_url_options = json_encode($faviconsUrlArr);

        $websiteModel->save();

        $urlTurn = 1;
        $fetchedCount = count($faviconsUrlArr);
        foreach ($faviconsUrlArr as $url){
//            echo 'STARTED: '.$url.' WS id = '.$websiteModel->id." Total fetched = ".$fetchedCount." --> this turn = ".$urlTurn." ".PHP_EOL;
            $this->rollingCurlForImages->get($url, null, [CURLOPT_NOBODY => true], ['model' => $websiteModel, 'turn' => $urlTurn, 'fetchedCount' => $fetchedCount]);
            $urlTurn++;
        }
//        echo "Updated ".$websiteModel->url . PHP_EOL;
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

        if($fetchedCount == $turn){
            $websiteModel->image_url = $this->imagesResults[$websiteModel->id]['url'];
            $websiteModel->is_image_setted = 1;
            $websiteModel->save();
        }

//        echo $responseInfo['url'].' WS id = '.$websiteModel->id." Total fetched = ".$fetchedCount." --> this turn = ".$turn." status ".$turnStatus.PHP_EOL;
    }

}