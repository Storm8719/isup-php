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

    public function init(){
        $this->observers = new \SplObjectStorage();
        $this->rc = new RollingCurlCustom();
        $this->rc->setSimultaneousLimit($this->simultaneousLimit);
        $this->rc->setCallback(function(\RollingCurl\Request $request, RollingCurlCustom $rc){
            $this->sendFetchingResults($request);
            $rc->clearCompleted();
        });
    }

    public function addUrlToCheckArr($sites){
        foreach ($sites as $site){
            $this->addUrlToCheck($site);
        }
    }

    public function execute(){
        $this->rc->execute();
    }

    public function attach(CheckerObserver $observer)
    {
        echo "Subject: Attached an observer.\n";
        $this->observers->attach($observer);
    }

    public function detach(CheckerObserver $observer)
    {
        $this->observers->detach($observer);
        echo "Subject: Detached an observer.\n";
    }

    public function notify()
    {
        echo "Subject: Notifying observers...\n";
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }


    public abstract function addUrlToCheck(\app\models\Sites $site);

    public abstract function sendFetchingResults(\RollingCurl\Request $request);

}