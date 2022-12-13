<?php


namespace app\commands;


use app\components\UrlHelper;
use app\models\HtmlParser;
use app\models\Sites;
use yii\console\ExitCode;
use app\models\RollingCurlCustom;

class StartController extends \yii\console\Controller
{

    public $rollingCurl;

    public function actionIndex($message = 'hello world')
    {

        $this->startDaemon();

        return ExitCode::OK;
    }

    public function startDaemon(){

        $this->rollingCurl = new RollingCurlCustom();
        $this->rollingCurl->setSimultaneousLimit(7);

        $sites = Sites::find()->where(['status' => 0])->all();

        $start = microtime(true);
        echo "Fetching..." . PHP_EOL;

        foreach ($sites as $site){
            $url = ($site->scheme ? $site->scheme.'://'.$site->url : 'https://'.$site->url);
            $this->rollingCurl->get($url,null, [CURLOPT_NOBODY => false], ['model' => $site])->setCallback(
                function(\RollingCurl\Request $request, RollingCurlCustom $rollingCurl){
                    $this->setFetchingResults($request);
                    $rollingCurl->clearCompleted();
                    $rollingCurl->prunePendingRequestQueue();
                });
        }


//        try {
            $this->rollingCurl->execute();
//        } catch (\Exception $e) {
//            echo "error executing";
////            var_dump($e);
//        }
        echo "...done in " . (microtime(true) - $start) . PHP_EOL;
    }


    private function setFetchingResults(\RollingCurl\Request $request){

        $responseInfo = $request->getResponseInfo();

        $websiteModel = $request->getExtraInfo()['model'];
        $fullUrl = $websiteModel->scheme ? $websiteModel->scheme.'://'.$websiteModel->url : 'https://'.$websiteModel->url;
        $parser = new HtmlParser($request->getResponseText(), $fullUrl);
        $websiteModel->last_http_code = (int) $responseInfo['http_code'];
        $websiteModel->ttfb = floatval($responseInfo['starttransfer_time']) * 1000; //ms
        $websiteModel->updated_at = time();
        $websiteModel->header = $parser->getTitle();
        $websiteModel->description = $parser->getDescription();
        $websiteModel->image_url = $parser->getFaviconUrl();
        $websiteModel->save();

        echo "Updated ".$websiteModel->url . PHP_EOL;

//        var_dump($request->getResponseInfo());
//        var_dump($request->getResponseText());

    }

}