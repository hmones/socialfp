<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Scrapper;
use App\Jobs\CrawlerJob;
use Spatie\Crawler\Url;
//use DB;



class CrawlerJobTest extends TestCase
{

    public function testProcessed()
    {
        $job = new CrawlerJob('http://hadanty.net');
        $this->assertFalse($job->alreadyProcessed('http://hadanty.net'));
        $this->assertTrue($job->alreadyProcessed('http://hadanty.net'));
    }


    public function testGetAllLinks()
    {
        $url = 'http://www.radiosarajevo.ba/';
        $scrapper = new Scrapper();
        $parser = $scrapper->scrap($url);

        $job = new CrawlerJob($url);
        $links = $job->getAllLinks($scrapper->crawler());
        $this->assertTrue(count($links) > 0);
        // foreach($links as $link){
        //     echo "$link \n";
        // }
        $job->crawlAllLinks($links);

    }

    public function testGetAllLinksNotArticle()
    {
        $url = 'http://www.radiosarajevo.ba/';
        $scrapper = new Scrapper();
        $parser = $scrapper->scrap($url);

        $job = new CrawlerJob($url);
        $links = $job->getAllLinks($scrapper->crawler());
        $this->assertTrue(count($links) > 0);
    }


    public function testNormalizeUrl(){
        $job = new CrawlerJob('http://www.radiosarajevo.ba/');
        $url = Url::create('/novost/218062/predsjednistvo-bih-uradimo-malo-vise-za-bih-i-svima-ce-nam-biti-bolje-foto');
        $newUrl = $job->normalizeUrl($url);
        $this->assertEquals((string)$newUrl, 'http://www.radiosarajevo.ba/novost/218062/predsjednistvo-bih-uradimo-malo-vise-za-bih-i-svima-ce-nam-biti-bolje-foto');
    }


    public function testSave()
    {
        //$this->assertTrue(count(DB::table('jobs')->get())  == 0 );
        $url = 'http://www.radiosarajevo.ba/novost/218062/predsjednistvo-bih-uradimo-malo-vise-za-bih-i-svima-ce-nam-biti-bolje-foto';
        $job = new CrawlerJob($url);
        $scrapper = new Scrapper();
        $parser = $scrapper->scrap($url);
        $crawler = $scrapper->crawler();
        $job->savePage($parser);
        //$this->assertTrue(count(DB::table('jobs')->get()) > 0 );

    }






}
