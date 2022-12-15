<?php


namespace app\daemon;

use Yii;

class MainChecker extends Checker
{

    protected $observers;

    public function __construct()
    {
        parent::__construct();
    }

    public function addUrlToCheck(\app\models\Sites $site){

        $url = ($site->scheme ? $site->scheme.'://'.$site->url : 'https://'.$site->url);
        Yii::$app->l->log('Added to MainChecker '.$url);
        $this->rc->get($url, null, $this->curlOptions, ['model' => $site]);
    }

    public function sendFetchingResults(\RollingCurl\Request $request){
        foreach ($this->observers as $observer) {
            $observer->setResults(['type' => 'mainCheck', 'request'=> $request]);
//            $observer->setHtmlCheckResults($request);
        }
    }

}