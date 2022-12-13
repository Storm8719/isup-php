<?php


namespace app\components;

use yii\base\Component;


class UrlHelper extends Component
{
    public $parsedUrl;
    public $parsedReference;

    public function parseUrl($url){
        return parse_url($url);
    }

    public function normalizeUrl($url, $reference){

        $parsedUrl = $this->parseUrl($reference);
        if(!$parsedUrl)
            return false;

        if(isset($parsedUrl['host']) && isset($parsedUrl['scheme']))
            return $url;

        $mainParsedUrl = parse_url($reference);
        if(count($mainParsedUrl)<=1 && $mainParsedUrl['path'] == "" )
            return $url;

        return ((isset($mainParsedUrl['scheme'])) ? $mainParsedUrl['scheme']."://" : '').
            $mainParsedUrl['host'] .(substr($mainParsedUrl['host'], -1) == '/' ? '' : '/').
//            (substr($parsedUrl['path'], 1) == '/' ? substr($parsedUrl['path'], 1) : $parsedUrl['path']).
            $parsedUrl['path'].
            (isset($parsedUrl['query']) ? '?'.$parsedUrl['query'] : '');
    }

}