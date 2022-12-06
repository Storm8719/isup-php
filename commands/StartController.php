<?php


namespace app\commands;


use yii\console\ExitCode;

class StartController extends \yii\console\Controller
{

    public function actionIndex($message = 'hello world')
    {

        $rollingCurl = new \RollingCurl\RollingCurl();

        $rollingCurl
            ->setSimultaneousLimit(15)
            ->setCallback(
                function(\RollingCurl\Request $request, \RollingCurl\RollingCurl $rollingCurl) {
                    $this->setFetchingResults($request);
                });

        $rollingCurl->get("www.php.net",null, [CURLOPT_NOBODY => true]);
        try {
            $rollingCurl->execute();
        } catch (\Exception $e) {
            var_dump($e);
        }
        echo $message . "\n";

        return ExitCode::OK;
    }

    private function setFetchingResults(\RollingCurl\Request $request){
        var_dump($request->getUrl());
        var_dump($request->getResponseInfo());

    }

}