<?php


namespace app\daemon;


interface CheckerSubject
{

    /**
     * Attach an SplObserver
     * @param CheckerObserver $observer <p>
     * @return void
     */
    public function attach (CheckerObserver $observer);

    /**
     * Detach an observer
     * @param CheckerObserver $observer <p>
     * @return void
     */
    public function detach (CheckerObserver $observer);

}