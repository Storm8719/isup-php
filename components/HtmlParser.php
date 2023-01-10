<?php


namespace app\components;

use ForceUTF8\Encoding;
use Yii;

class HtmlParser
{

    public $htmlText = null;
    public $url = null;
    private $headTagHtml = null;
    private $charset;

    public function __construct($html, $url, $contentType = null)
    {
        $this->setData($html, $url, $contentType);
    }

    public function setData($html = null, $url = null, $contentType = null){
        $this->htmlText = $html ? $html : $this->htmlText;
        $this->url = $url ? $url : $this->url;
        $this->setHeadTagHtml();
        $this->charset = $this->getCharsetFromContentType($contentType);
    }

    private function getCharsetFromContentType($contentType){
        if(preg_match('~.*?(windows-1251|utf-8).*?~is', $contentType, $match))
            return $match[1];
        return "utf-8";
    }

    public function getFaviconUrlCandidatesArray(){
        return self::getFaviconFromHtml($this->headTagHtml, $this->url);
    }

    public function getDescription(){
        return $this->convertStrByCharset($this->getDescriptionFromHtml());
    }

    public function getTitle(){
        return $this->convertStrByCharset($this->getTitleFromHtml());
    }

    private function convertStrByCharset($str){
        if($this->charset == 'utf-8')
            return Encoding::toUTF8($str);
        return mb_convert_encoding($str, "utf-8", "windows-1251");
    }

    private function setHeadTagHtml(){
        preg_match('~<head.*?>(.*?)</head>~is', $this->htmlText, $headHtml);
        $this->headTagHtml = isset($headHtml[0]) ? $headHtml[0] : null;
    }

    public static function getFaviconFromHtml($html, $url)
    {
        $possibleCandidates = [];

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

        if(preg_match($svgFaviconPattern, $html, $svg)){
            $url = Yii::$app->urlHelper->normalizeUrl($svg[1], $url);
            $possibleCandidates[] = $url;
        }

        preg_match_all('~<link.*?rel=".*?icon".*?>~is', $html, $linksMatches);

        foreach ($linksMatches[0] as $linkTag) {

            if (preg_match('/sizes=.*?(?:(?:\d{3,}|[6-9]\d)x(?:\d{3,}|[6-9]\d)|.*?any.*?)/', $linkTag, $res)) {
                preg_match('/href="(.*?)"/', $linkTag, $match);
                $url = Yii::$app->urlHelper->normalizeUrl($match[1], $url);
                $possibleCandidates[] = $url;
            }elseif(preg_match('/href="(.*?)"/', $linkTag, $match)){
                $url = Yii::$app->urlHelper->normalizeUrl($match[1], $url);
                $possibleCandidates[] = $url;
            }
        }

        return $possibleCandidates;
    }


    private function getDescriptionFromHtml(){
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