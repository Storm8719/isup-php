<?php


namespace app\commands;


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

        print_r($request->getResponseInfo());
            die;

        $websiteModel = $request->getExtraInfo()['model'];
        $parser = new HtmlParser($request->getResponseText(), ($websiteModel->scheme ? $websiteModel->scheme.'://'.$websiteModel->url : 'https://'.$websiteModel->url));
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