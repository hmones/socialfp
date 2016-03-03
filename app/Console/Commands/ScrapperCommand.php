<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Scrapper\CustomCrawler as Crawler;
use Config;
use \App\Jobs\CrawlerJob;
class ScrapperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrapper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapper Command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        foreach (Config::get('portals') as $url => $parser) {
              dispatch(new CrawlerJob($url, 1));
        }
        //
        //(new CrawlerJob())->handle('http://www.biscani.net/', 4);




        //
        // $crawler = new Crawler();
        //
        //
        // $crawler->enableCookieHandling(true);
        // $crawler->setCrawlingDepthLimit(4);
        // $crawler->setRequestLimit(800,true);//Sets a limit to the total number of requests the crawler should execute. True is to select only successfully received documents
        // $crawler->excludeLinkSearchDocumentSections(7); //2 means comment section is excluded, 1 means the script section, 7 means all special sections
        // $crawler->addContentTypeReceiveRule("#text/html#");
        // $crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png|css|js|svg|json)# i");
        //
        // foreach (Config::get('portals') as $url => $parser) {
        //     $crawler->setURL($url);
        //     $crawler->setParser($parser);
        //     $crawler->go();
        //     echo $url;
        //     //
        //     // $contentDefInstance = new $portal->contentDef;
        //     // echo $contentDefInstance->title();
        // }
    }
}
