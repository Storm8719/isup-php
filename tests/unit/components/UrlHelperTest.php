<?php


namespace tests\unit\components;


use app\components\UrlHelper;
use Yii;

class UrlHelperTest extends \Codeception\Test\Unit
{
    public function testNormalizeUrl()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('https://php.net/', 'http://php.net/'))->equals('https://php.net/');

    }

    public function testNormalize2()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('https://php.net/', ''))->equals('https://php.net/');
    }

    public function testNormalize3()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('https://php.net', ''))->equals('https://php.net');
    }

    public function testNormalize4()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('/favicon.png', 'http://php.net/'))->equals('http://php.net/favicon.png');
    }

    public function testNormalize5()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('favicon.png', 'http://php.net'))->equals('http://php.net/favicon.png');
    }

    public function testNormalize6()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('/favicon.png/', 'http://php.net'))->equals('http://php.net/favicon.png/');
    }

    public function testNormalize7()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('favicon.png/', 'http://php.net'))->equals('http://php.net/favicon.png/');
    }

    public function testNormalize8()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('favicon.png//', 'http://php.net'))->equals('http://php.net/favicon.png/');
    }

    public function testNormalize9()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('www.php.net/favicon.png', 'http://php.net'))->equals('http://php.net/favicon.png');
    }


    public function testParseUrl()
    {
        $h = new UrlHelper();
        expect($h->getUrlSchemeAndHost('https://www.php.net'))->equals(['scheme' => 'https', 'host' => "www.php.net"]);
    }

    public function testParseUrl2()
    {
        $h = new UrlHelper();
        expect($h->getUrlSchemeAndHost('https://www.php.net/'))->equals(['scheme' => 'https', 'host' => "www.php.net"]);
    }

    public function testParseUrl3()
    {
        $h = new UrlHelper();
        expect($h->getUrlSchemeAndHost('http://www.php.net'))->equals(['scheme' => 'http', 'host' => "www.php.net"]);
    }

    public function testParseUrl4()
    {
        $h = new UrlHelper();
        expect($h->getUrlSchemeAndHost('http://www.php.net/'))->equals(['scheme' => 'http', 'host' => "www.php.net"]);
    }

    public function testParseUrl5()
    {
        $h = new UrlHelper();
        expect($h->getUrlSchemeAndHost('http:/www.php.net/'))->equals(null);
    }

    public function testParseUrl6()
    {
        $h = new UrlHelper();
        expect($h->getUrlSchemeAndHost('www.php.net'))->equals(['scheme' => 'https', 'host' => "www.php.net"]);
    }

    public function testParseUrl7()
    {
        $h = new UrlHelper();
        expect($h->getUrlSchemeAndHost('www.php.net/some'))->equals(['scheme' => 'https', 'host' => "www.php.net"]);
    }

    public function testParseUrl8()
    {
        $h = new UrlHelper();
        expect($h->getUrlSchemeAndHost('www.php.net/some/?s==123'))->equals(['scheme' => 'https', 'host' => "www.php.net"]);
    }

    public function testParseUrl9()
    {
        $h = new UrlHelper();
        expect($h->getUrlSchemeAndHost('php.net/some/?s==123'))->equals(['scheme' => 'https', 'host' => "php.net"]);
    }


}