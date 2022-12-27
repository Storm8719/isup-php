<?php


namespace app\components;

use yii\base\Component;


class UrlHelper extends Component
{

    public function getUrlSchemeAndHost($url){
        $scheme = $this->getUrlScheme($url);
        if(!$scheme)
            $scheme = 'https';
        $host = $this->getUrlHost($url);
        if($host)
            return ['scheme' => $scheme, 'host' => $host];
        return null;
    }


    public function getUrlHost($url){
        if(filter_var($url, FILTER_VALIDATE_URL, [FILTER_FLAG_HOST_REQUIRED])){
            $urlArr = parse_url($url);
            return $urlArr['host'];
        }

        $regex = "((https?|ftp)\:\/\/)?";
        $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";
        $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})";
        $regex .= "(\:[0-9]{2,5})?";
        $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?";
        $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?";
        $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?";

        if (!preg_match("/^$regex$/i", $url))
            return null;

        if(preg_match("/^(.*?)\//is", $url, $match))
            return $match[1];

        return $url;
    }


    public function getUrlScheme($url){
        $urlArr = parse_url($url);
        if(isset($urlArr['scheme']))
            return $urlArr['scheme'];

        if(preg_match("/^(?:https?).*?/is", $url, $match))
            return $match[0];

        return null;
    }

    /**
     * @param string $url
     * @return string|null
     */
    public function getImageFormat($url){
        if(preg_match("/(?<=\.)[^.]+$/is", parse_url($url)['path'], $match)) {
            $allowedFormats = ['png', 'svg', 'ico', 'jpg', 'jpeg', 'gif', 'webp'];
            if(in_array($match[0], $allowedFormats))
                return $match[0];
        }
        return null;
    }

    /**
     * Function for getting full url from 2 parts
     * 1st part($url) = relative or full url that we need to return full
     * if $url already full - returning as it is.
     * If $url not full - then we get protocol and hostname from $reference and composing it with $url
     * @param string $url
     * @param string $reference
     * @return false|string
     */
    public function normalizeUrl($url, $reference){

        $parsedUrl = parse_url($url);
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