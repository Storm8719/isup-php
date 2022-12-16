<?php


namespace app\daemon;


use app\components\HtmlParser;
use app\models\Sites;
use Yii;

class WebsiteCheckerService implements CheckerObserver
{
    private $htmlChecker;
    private $faviconChecker;
    public $isNeedToCheckFavicon = true;

    public function __construct()
    {
        $this->htmlChecker = new MainChecker();
        $this->htmlChecker->attach($this);
        $this->faviconChecker = new FaviconChecker();
        $this->faviconChecker->attach($this);
    }

    /**
     * Function for checking one site without favicon
     * @param Sites $websiteModel
     * @return Sites
     */
    public function checkAndSaveOneWebsite(\app\models\Sites $websiteModel)
    {
        $this->isNeedToCheckFavicon = false;
        $this->htmlChecker->addUrlToCheck($websiteModel);
        $this->htmlChecker->execute();
        return $websiteModel;
    }

    /**
     * Function for checking many websites. $websitesArr must be array of \app\models\Sites objects
     * @param array $websitesArr
     * @return array
     */
    public function checkAndSaveWebsites($websitesArr)
    {
        $this->htmlChecker->addUrlToCheckArr($websitesArr);
        $this->htmlChecker->execute();
        $this->faviconChecker->execute();
        return $websitesArr;
    }

    /**
     * Function for distribution results that given from Subjects
     * @param array $data
     * @return void
     */
    public function setResults($data)
    {
        if ($data['type'] == 'faviconCheck') {
            $this->setFaviconCheckResults($data['model'], $data['urlToSet']);
        } elseif ($data['type'] == 'mainCheck') {
            $this->setHtmlCheckResults($data['request']);
        }
    }


    /**
     * Process what we got from \app\daemon\MainChecker and save the result.
     * Also call the favicons check, if necessary
     * @param \RollingCurl\Request $request
     * @return boolean
     */
    private function setHtmlCheckResults(\RollingCurl\Request $request)
    {


        $responseInfo = $request->getResponseInfo();
        $websiteModel = $request->getExtraInfo()['model'];
        Yii::$app->l->log('Html Check Result for ' . $websiteModel->url . ' given');
        $parser = new HtmlParser($request->getResponseText(), $responseInfo['url']);
        $websiteModel->last_http_code = (int)$responseInfo['http_code'];
        $websiteModel->updated_at = time();

        if (!$websiteModel->last_http_code) {
            $websiteModel->status = -1;
            return $websiteModel->save();
        }

        $websiteModel->status = 1;

        if ($responseInfo['redirect_count'])
            $websiteModel->redirect_to = $responseInfo['url'];

        $websiteModel->ttfb = floatval($responseInfo['starttransfer_time']) * 1000; //ms
        $websiteModel->title = $parser->getTitle();
        $websiteModel->description = $parser->getDescription();

        if ($websiteModel->is_image_setted || !$this->isNeedToCheckFavicon)
            return $websiteModel->save();

        $faviconsUrlArr = $parser->getFaviconUrlCandidatesArray();
        $websiteModel->image_url_options = json_encode($faviconsUrlArr);
        $websiteModel->save();

        if (!empty($faviconsUrlArr)) {
            Yii::$app->l->log('Favicon request for ' . $websiteModel->url . ' sended...');
            $this->faviconChecker->addUrlToCheck($websiteModel);
        }
        return true;
    }

    /**
     * Process what we got from \app\daemon\FaviconChecker and save the result.
     * @param Sites $websiteModel
     * @param string|null $imageUrlStr
     * @return void
     */
    private function setFaviconCheckResults(\app\models\Sites $websiteModel, $imageUrlStr)
    {
        Yii::$app->l->log('Favicon result for ' . $websiteModel->url . ' given: ' . $imageUrlStr);
        $websiteModel->image_url = $imageUrlStr;
        $websiteModel->is_image_setted = 1;
        $websiteModel->save();
    }
}