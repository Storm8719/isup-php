<?php


namespace app\commands;


use app\components\UrlHelper;
use app\models\HtmlParser;
use app\models\Sites;
use GuzzleHttp\Promise\Promise;
use yii\console\ExitCode;
use app\models\RollingCurlCustom;

class StartController extends \yii\console\Controller
{

    public $rollingCurl;
    public $rollingCurlForImages;

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
            $rollingCurl->prunePendingRequestQueue();
        });
        $this->rollingCurlForImages = new RollingCurlCustom();
        $this->rollingCurlForImages->setSimultaneousLimit(7);
        $this->rollingCurlForImages->setCallback(function(\RollingCurl\Request $request, RollingCurlCustom $rollingCurl){
            $this->setFetchingResultsForImages($request);
            $rollingCurl->clearCompleted();
            $rollingCurl->prunePendingRequestQueue();
        });

//        var_dump($this->rollingCurlForImages === $this->rollingCurl);
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

        foreach ($faviconsUrlArr as $url){
            $this->rollingCurlForImages->get($url, null, [CURLOPT_NOBODY => false], ['model' => $websiteModel]);
        }
        echo "Updated ".$websiteModel->url . PHP_EOL;
    }

    private function setFetchingResultsForImages(\RollingCurl\Request $request){
        echo 'setFetchingResultsForImages requested'.PHP_EOL;
    }

}