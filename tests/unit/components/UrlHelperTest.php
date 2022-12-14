<?php


namespace tests\unit\components;


use app\components\UrlHelper;

class UrlHelperTest extends \Codeception\Test\Unit
{
    public function testUrl()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('https://php.net/', 'http://php.net/'))->equals('https://php.net/');

    }

    public function testUrl2()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('https://php.net/', ''))->equals('https://php.net/');
    }

    public function testUrl3()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('https://php.net', ''))->equals('https://php.net');
    }

    public function testUrl4()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('/favicon.png', 'http://php.net/'))->equals('http://php.net/favicon.png');
    }

    public function testUrl5()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('favicon.png', 'http://php.net'))->equals('http://php.net/favicon.png');
    }

    public function testUrl6()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('/favicon.png/', 'http://php.net'))->equals('http://php.net/favicon.png/');
    }

    public function testUrl7()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('favicon.png/', 'http://php.net'))->equals('http://php.net/favicon.png/');
    }

    public function testUrl8()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('favicon.png//', 'http://php.net'))->equals('http://php.net/favicon.png/');
    }

    public function testUrl9()
    {
        $h = new UrlHelper();
        expect($h->normalizeUrl('www.php.net/favicon.png', 'http://php.net'))->equals('http://php.net/favicon.png');
    }

//    public function testUrl10()
//    {
//        $h = new UrlHelper();
//    }
//
//    public function testUrl11()
//    {
//        $h = new UrlHelper();
//    }


}