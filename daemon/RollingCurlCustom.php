<?php


namespace app\daemon;


use RollingCurl\Request;
use RollingCurl\RollingCurl;

class RollingCurlCustom extends RollingCurl
{

    /**
     * Create new Request and add it to the request queue
     *
     * @param string $url
     * @param string $method
     * @param array|string $postData
     * @param array $headers
     * @param array $options
     * @param array $extraOptions
     * @return RollingCurl
     */
    public function request($url, $method = "GET", $postData = null, $headers = null, $options = null, $extraOptions = null)
    {
        $newRequest = new Request($url, $method);
        if ($postData) {
            $newRequest->setPostData($postData);
        }
        if ($headers) {
            $newRequest->setHeaders($headers);
        }
        if ($options) {
            $newRequest->setOptions($options);
        }
        if($extraOptions){
            $newRequest->setExtraInfo($extraOptions);
        }
        return $this->add($newRequest);
    }

    /**
     * Perform GET request
     *
     * @param string $url
     * @param array $headers
     * @param array $options
     * @param array $extraOptions
     * @return RollingCurl
     */
    public function get($url, $headers = null, $options = null, $extraOptions = null)
    {
        return $this->request($url, "GET", null, $headers, $options, $extraOptions);
    }

}