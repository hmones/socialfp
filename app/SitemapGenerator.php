<?php

namespace App;

use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlObserver;
use \Spatie\Crawler\Url;
use \Spatie\Crawler\ResponseInterface;
use \Spatie\Crawler\CrawlProfile;

use Log;

class SitemapGenerator
{
    public static function start($url, array $skipUrls = [], $limit = 100)
    {
        $globalLimit = $limit;
        $urlCrowler = new UrlCrawler($globalLimit);
        $urlSkipper = new UrlSkipper($url, $skipUrls, $globalLimit);


        Crawler::create()
            ->setCrawlObserver($urlCrowler)
            ->setCrawlProfile($urlSkipper)
            ->startCrawling($url);

        return $urlCrowler->getUrls();
    }
}

class UrlSkipper implements CrawlProfile{

    private $url;
    private $skipUrls = [];
    private $limit;

    function __construct($url, array $skipUrls, &$globalLimit){
        $this->url = Url::create($url);
        $this->skipUrls = $skipUrls;
        $this->limit = &$globalLimit;
    }

    public function shouldCrawl(Url $url){

        if(!$this->isInside($url)) return FALSE; //crawl same host only
        if($this->shouldSkip()) return FALSE;
        if($this->exceedLimit()) return FALSE;

        return TRUE;
    }

    private function isInside(Url $url){
        return $this->url->host == $url->host;
    }

    private function exceedLimit(){
        // check the limit of the $globalLimit
        return $this->limit < 0;
    }


    private function shouldSkip(){
        if(empty($this->skipUrls)) return FALSE; //nothing to skip

        $found = in_array($url, $this->skipUrls);

        if($found !== FLASE){
            Log::debug('Skipping URL: '. $url);
            return TRUE;
        }

        return FALSE;
    }
}

class UrlCrawler implements CrawlObserver
{
    private $urls = [];
    private $limit;

    function __construct(&$globalLimit){
        $this->limit = &$globalLimit;
    }

    public function getUrls(){
        return $this->urls;
    }


    public function willCrawl(Url $url)
    {
        $this->limit--;
        echo "$this->limit " . $url . "\n";
        array_push($this->urls, $url);
    }

    public function hasBeenCrawled(Url $url, $response){}

    public function finishedCrawling()
    {
        Log::debug('Crawled ' . count($this->urls) . ' Urls');
    }
}
