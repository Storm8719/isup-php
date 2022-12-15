<?php


namespace app\daemon;


interface CheckerObserver
{
    /**
     * Receive update from subject
     *
     * @param array $data
     * @return void
     */
    public function setResults($data);
}