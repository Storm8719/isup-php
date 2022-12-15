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
        $this->simultaneousLimit = 10;
        $this->curlOptions = [CURLOPT_NOBODY => true, CURLOPT_TIMEOUT => 3];
    }


    /**
     * Add One url in queue
     *
     * For each url we set $turn - a priority variable that will decide which url will be set for the site if several
     * urls are valid
     *
     * @param \app\models\Sites $site
     */
    public function addUrlToCheck(\app\models\Sites $site)
    {
        $faviconsUrlArr = json_decode($site->image_url_options);
        $urlTurn = 1;
        $fetchedCount = count($faviconsUrlArr);
        foreach ($faviconsUrlArr as $url) {
            Yii::$app->l->log('Added to FaviconChecker. Site: ' . $site->url . ' Fav url: ' . $url . ' Count: ' . $fetchedCount . ' --> turn ' . $urlTurn);
            $this->rc->get($url, null, $this->curlOptions, ['model' => $site, 'turn' => $urlTurn, 'fetchedCount' => $fetchedCount]);
            $urlTurn++;
        }
    }

    /**
     * Collect fetch results from fetching all urls to $this->imagesResults
     * When get the last result, send the highest priority($turn) url to the Observer
     *
     * @param \RollingCurl\Request $request
     */
    public function sendFetchingResults(\RollingCurl\Request $request)
    {

        $websiteModel = $request->getExtraInfo()['model'];
        $turn = $request->getExtraInfo()['turn'];
        $responseInfo = $request->getResponseInfo();
        $turnStatus = ($responseInfo['http_code'] == '200' && preg_match('/.*?image.*?/', $responseInfo['content_type'], $m));

        if ($turnStatus && ((!isset($this->imagesResults[$websiteModel->id])) || $this->imagesResults[$websiteModel->id]['turn'] > $turn)) {
            $this->imagesResults[$websiteModel->id] = ['turn' => $turn, 'url' => $responseInfo['url']];
        }

        Yii::$app->l->log('FaviconChecker RESULT given: Site: ' . $websiteModel->url . ' Fav url: ' . $responseInfo['url'] . ' Count: ' . $request->getExtraInfo()['fetchedCount'] . ' --> turn ' . $turn . ' status: ' . $turnStatus);

        if ($request->getExtraInfo()['fetchedCount'] == $turn) {
            Yii::$app->l->log('FaviconChecker sended to observer Favicon check result: Site: ' . $websiteModel->url . ' Fav url: ' . $this->imagesResults[$websiteModel->id]['url']);
            $resultUrl = isset($this->imagesResults[$websiteModel->id]['url']) ? $this->imagesResults[$websiteModel->id]['url'] : null;
            foreach ($this->observers as $observer) {
                $observer->setResults(['type' => 'faviconCheck', 'model' => $websiteModel, 'urlToSet' => $resultUrl]);
//                $observer->setFaviconCheckResults($websiteModel, $this->imagesResults[$websiteModel->id]['url']);
            }
            unset($this->imagesResults[$websiteModel->id]);
        }

    }
}