<?php


namespace app\models;


use GuzzleHttp\Exception\GuzzleException;

class HtmlParser
{

    public $htmlText = null;
    public $url = null;
    protected $headTagHtml = null;

    public function __construct($html, $url)
    {
        $this->setData($html, $url);
    }

    public function setData($html = null, $url = null){
        $this->htmlText = $html ? $html : $this->htmlText;
        $this->url = $url ? $url : $this->url;
        $this->setHeadTagHtml();
    }

    public function getFaviconUrl(){
        return $this->getFaviconFromHtml();
    }

    public function getDescription(){
        return $this->getDescriptionFromHtml();
    }

    public function getTitle(){
        return $this->getTitleFromHtml();
    }

    protected function setHeadTagHtml(){
        preg_match('~<head.*?>(.*?)</head>~is', $this->htmlText, $headHtml);
        $this->headTagHtml = isset($headHtml[0]) ? $headHtml[0] : null;
    }

    protected function getFaviconFromHtml()
    {
        $svgFaviconPattern = '
  ~<\s*link\s
    (?=[^>]*?
    \b(?:rel)\s*=\s*
    (?|".*?icon")
  )
  [^>]*?\bhref\s*=\s*
    (?|"\s*([^"]*?.svg)\s*"|\'\s*([^\']*?.svg)\s*\')
  [^>]*>
  
  ~ix';

        if(preg_match($svgFaviconPattern, $this->headTagHtml, $svg)){
//            var_dump($svg[1]);
//            return $svg[1];
            $url = $this->normalizeUrl($svg[1]);
            if($this->isImageUrlCheck($url)){
                return $url;
            }
        }

        preg_match_all('~<link.*?rel=".*?icon".*?>~is', $this->headTagHtml, $linksMatches);
        $tempFavicons = [];

        foreach ($linksMatches[0] as $linkTag) {

            if (preg_match('/sizes=.*?(?:(?:\d{3,}|[6-9]\d)x(?:\d{3,}|[6-9]\d)|.*?any.*?)/', $linkTag, $res)) {
                preg_match('/href="(.+)"/', $linkTag, $match);
                $url = $this->normalizeUrl($match[1]);
                if($this->isImageUrlCheck($url)){
                    return $url;
                }
            }
//            else{
//                $tempFavicons[] = $linkTag;
//            }
        }
        return "no_favicon_found";
    }

    protected function isImageUrlCheck($url){



        $client = new \GuzzleHttp\Client();
        try{
            $response = $client->request('GET', $url);
        } catch (GuzzleException $e) {
            echo "error on isImageUrlCheck url ".$url;
            return false;
        }
        return ($response->getStatusCode() == 200 && preg_match('/.*?image.*?/', $response->getHeaderLine('content-type'), $m));
    }

    public function normalizeUrl($url){
        $parsedUrl = parse_url($url);
        if(!$parsedUrl)
            return false;

        if(isset($parsedUrl['host']) && isset($parsedUrl['scheme']))
            return $url;

        $mainParsedUrl = parse_url($this->url);
        if(count($mainParsedUrl)<=1 && $mainParsedUrl['path'] == "" )
            return $url;

        return ((isset($mainParsedUrl['scheme'])) ? $mainParsedUrl['scheme']."://" : '').
            $mainParsedUrl['host'] .(substr($mainParsedUrl['host'], -1) == '/' ? '' : '/').
//            (substr($parsedUrl['path'], 1) == '/' ? substr($parsedUrl['path'], 1) : $parsedUrl['path']).
            $parsedUrl['path'].
            (isset($parsedUrl['query']) ? '?'.$parsedUrl['query'] : '');
    }

    protected function getDescriptionFromHtml(){
        $pattern = '
  ~<\s*meta\s

    (?=[^>]*?
    \b(?:name)\s*=\s*
    (?|"Description")
  )
  [^>]*?\bcontent\s*=\s*
    (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
    ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
  [^>]*>
  ~ix';
        if(preg_match($pattern, $this->headTagHtml, $match))
            return $match[1];
        return "No description found";
    }

    protected function getTitleFromHtml(){
        if(preg_match('~<title.*?>(.*?)</title>~is', $this->headTagHtml, $match))
            return $match[1];
        return "No title found";
    }

}