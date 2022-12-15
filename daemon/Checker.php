<?php


namespace app\daemon;


abstract class Checker
{
    protected $observers;
    public $rc;
    public $simultaneousLimit = 5;
    public $curlOptions = [CURLOPT_NOBODY => false, CURLOPT_TIMEOUT => 8];

    public function __construct()
    {
        $this->init();
    }

    /**
     * Init Checker process
     * @return void
     */
    public function init()
    {
        $this->observers = new \SplObjectStorage();
        $this->rc = new RollingCurlCustom();
        $this->rc->setSimultaneousLimit($this->simultaneousLimit);
        $this->rc->setCallback(function (\RollingCurl\Request $request, RollingCurlCustom $rc) {
            $this->sendFetchingResults($request);
            $rc->clearCompleted();
        });
    }

    /**
     * Add urls to queue. In array must be \app\models\Sites objects
     * @param array $sites
     * @return void
     */
    public function addUrlToCheckArr($sites)
    {
        foreach ($sites as $site) {
            $this->addUrlToCheck($site);
        }
    }

    /**
     * Executing RollingCurl when all needed urls already in queue
     */
    public function execute()
    {
        $this->rc->execute();
    }


    /**
     * Attach this checker to Observer
     * @param CheckerObserver $observer
     */
    public function attach(CheckerObserver $observer)
    {
        $this->observers->attach($observer);
    }

    /**
     * Detach this checker from Observer
     * @param CheckerObserver $observer
     */
    public function detach(CheckerObserver $observer)
    {
        $this->observers->detach($observer);
    }


    /**
     * Add one url to queue
     * @param \app\models\Sites $site
     */
    public abstract function addUrlToCheck(\app\models\Sites $site);

    /**
     * Send results to Observer
     * @param \RollingCurl\Request $request
     */
    public abstract function sendFetchingResults(\RollingCurl\Request $request);

}