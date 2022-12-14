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

        $parsedUrl = $this->parseUrl($url);
        if(!$parsedUrl)
            return false;

        if(isset($parsedUrl['host']) && isset($parsedUrl['scheme']))
            return $url;

        $referenceParsed = parse_url($reference);
        if(count($referenceParsed)<=1 && $referenceParsed['path'] == "" )
            return $url;

        if(count($parsedUrl)==1 && isset($parsedUrl['path'])){

            $pattern = sprintf('/%s(.*?)$/i',$referenceParsed['host']);
            if(preg_match($pattern, $parsedUrl['path'], $m)){
                $parsedUrl['path'] = $m[1];
                $parsedUrl['host'] = $referenceParsed['host'];
                $parsedUrl['scheme'] = $referenceParsed['scheme'];
            }
        }

        if(isset($parsedUrl['scheme'])){
            $protocol = $parsedUrl['scheme'].'://';
        }elseif(isset($referenceParsed['scheme'])){
            $protocol = $referenceParsed['scheme'].'://';
        }else{
            $protocol = "";
        }

        if(isset($parsedUrl['host'])){
            $host = $parsedUrl['host'];
        }elseif(isset($referenceParsed['host'])){
            $host = $referenceParsed['host'];
        }else{
            $host = "";
        }

        $host = $host.(substr($host, -1) == '/' ? '' : '/');

        if(isset($parsedUrl['path'])){
            $path = $parsedUrl['path'];
        }else{
            return false;
        }

        if(isset($parsedUrl['query'])){
            $query = '?'.$parsedUrl['query'];
        }else{
            $query = "";
        }

        return $protocol . str_replace('//', '/',$host . $path . $query);

    }

}