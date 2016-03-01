<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Haythameyd\PHPCrawl\PHPCrawler;
use App\results;
use DB;
use Log;

class crawlportals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will start the crawling process every day at 12:00pm and store results in the database';

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

      //$urls=array('www.hayat.ba','ba.n1info.com','www.glassrpske.com','www.faktor.ba','www.cazin.net','www.biscani.net','www.vecernji.ba','www.avaz.ba','www.abc.ba');

      $urls=array('www.klix.ba','www.ekskluziva.ba','balkans.aljazeera.net','www.radiosarajevo.ba','www.nezavisne.com','www.bljesak.info','www.fokus.ba','www.sportsport.ba','www.novi.ba','www.vijesti.ba','www.depo.ba','www.source.ba','www.bh-index.com','www.oslobodjenje.ba','www.krajina.ba','www.haber.ba','www.buka.com','www.hayat.ba','ba.n1info.com','www.glassrpske.com','www.faktor.ba','www.cazin.net','www.biscani.net','www.vecernji.ba','www.avaz.ba','www.abc.ba');

      // $crawler = new PHPCrawler();
      //   $crawler->setURL('http://www.avaz.ba/image_galleries/view?id=6015&article_id=221010&layout=ajax&template=article_images');
      //   $crawler->addContentTypeReceiveRule("#text/html#");
      //   $crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png|css|js)$# i");
      Log::info('Started Crawling Now');
      $crawler = new PHPCrawler();
      foreach ($urls as $url) {
        $crawler->setURL($url);
        $crawler->addContentTypeReceiveRule("#text/html#");
        $crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png|css|js|svg|json)# i");
        //Exclude specific categories in a url for a specific portal

          if($url == "www.avaz.ba"){
          $crawler->addURLFilterRule("/(kategorija\/auto-moto|kategorija\/zanimljivosti)/");
          }
          elseif($url == "www.klix.ba"){
          $crawler->addURLFilterRule("/(\/lifestyle|\/scitech|\/auto)/");
          }
          elseif($url == "www.ekskluziva.ba"){
          $crawler->addURLFilterRule("/(\/Paparazzo|\/Auto-moto|\/Lifestyle|\/ezabava)/");
          }
          elseif($url == "balkans.aljazeera.net"){
          $crawler->addURLFilterRule("/(\/vrijeme)/");
          }
          elseif($url == "www.nezavisne.com"){
          $crawler->addURLFilterRule("/(\/zivot-stil|\/zanimljivosti|\/automobili|\/nauka-tehnologija)/");
          }
          elseif($url == "www.bljesak.info"){
          $crawler->addURLFilterRule("/(\/lifestyle|\/automoto|\/sci-tech)/");
          }
          elseif($url == "www.fokus.ba"){
          $crawler->addURLFilterRule("/(\/magazin|\/tehnomanija)/");
          }
          elseif($url == "www.novi.ba"){
          $crawler->addURLFilterRule("/(\/porodica-i-zdravlje|\/arhitektura-i-dizajn|\/tech|\/zanimljivosti)/");
          }
          elseif($url == "www.vijesti.ba"){
          $crawler->addURLFilterRule("/(\/kategorija\/magazin)/");
          }
          elseif($url == "www.depo.ba"){
          $crawler->addURLFilterRule("/(\/kategorija\/zena-in|\/kategorija\/zurnal)/");
          }
          elseif($url == "www.source.ba"){
          $crawler->addURLFilterRule("/(\/kanal\/technomania|\/kanal\/lifestyle|\/kanal\/zanimljivo)/");
          }
          elseif($url == "www.bh-index.com"){
          $crawler->addURLFilterRule("/(\/c100-lifestyle-magazin|\/magazin\/|\/scitech\/|\/c102-auto\/c108-auto-industrija|\/c103-zanimljivosti)/");
          }
          elseif($url == "www.oslobodjenje.ba"){
          $crawler->addURLFilterRule("/(\/it-autosvijet|\/opusteno|\/recepti)/");
          }
          elseif($url == "www.krajina.ba"){
          $crawler->addURLFilterRule("/(\/kategorija\/magazin|\/kategorija\/tehnologija|\/kategorija\/zanimljivosti)/");
          }
          elseif($url == "www.haber.ba"){
          $crawler->addURLFilterRule("/(\/showbiz|\/lifestyle|\/sci-tech|\/autosvijet)/");
          }
          elseif($url == "ba.n1info.com"){
          $crawler->addURLFilterRule("/(\/Lifestyle|\/Showbiz|\/Sci-Tech)/");
          }
          elseif($url == "www.glassrpske.com"){
          $crawler->addURLFilterRule("/(\/tehnologija|\/zabava)/");
          }
          elseif($url == "www.faktor.ba"){
          $crawler->addURLFilterRule("/(\/rubrika\/magazin)/");
          }
          elseif($url == "www.cazin.net"){
          $crawler->addURLFilterRule("/(\/magazin|\/galerija)/");
          }
          elseif($url == "www.biscani.net"){
          $crawler->addURLFilterRule("/(\/category\/lifestyle|\/category\/magazin|\/category\/tech)/");
          }
          elseif($url == "www.vecernji.ba"){
          $crawler->addURLFilterRule("/(\/showbiz)/");
          }

        $crawler->enableCookieHandling(true);
        $crawler->setCrawlingDepthLimit(4);
        $crawler->setRequestLimit(500,true);//Sets a limit to the total number of requests the crawler should execute. True is to select only successfully received documents
        $crawler->excludeLinkSearchDocumentSections(7); //2 means comment section is excluded, 1 means the script section, 7 means all special sections
        $crawler->go();
        //$crawler->goMultiProcessed(5);
        $this->info($url.' has been crawled successfully');
        // unset($crawler);
       }
       Log::info('Crawling Ended now');
    }
}
