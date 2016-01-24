<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Haythameyd\PHPCrawl\PHPCrawler;

class searchController extends Controller
{

    protected $portalcounter=array('klix'=>0,'avaz'=>0,'nezavisne'=>0);

    public function tweetsinf($tweets)
    {
      $count=0;
      $retweets=0;
      $likes=0;
      $influential=0;
      foreach($tweets->statuses as $obj){
         $retweets=$retweets+$obj->retweet_count;
         $likes=$likes+$obj->favorite_count;
         $count++;
         if($obj->user->friends_count>1000)
         {
           $influential++;
         }
      }
      $data=array($likes,$retweets,$count,$influential);
      return $data;
    }

    public function twittersearch($query,$dateto,$locationgeo,$searchtype)
    {
      $consumer="3Hsr7qk9HEYseF9bpJA07Go6H";
      $consumersecret="q1TjkWCHcPKrJn0YCmtKMwIHaXURI7ip3z5eDxDd4G0GxLsUOi";
      $accesstoken = "3803581341-FAlmx3CbE2amgcMyD2AkGYuLdRa7kZ3QcDSxj5a";
      $accesstokensecret = "1JtROKIjMaIhP6IJGMPGbTyRTF9WMyq1RGclpdvcKzZWD";
      $twitter= new TwitterOAuth($consumer,$consumersecret,$accesstoken,$accesstokensecret);
      $url="search/tweets";
      $tweets = $twitter->get($url, array('q'=>$query,'result_type'=>'recent','count'=>'200','until'=>$dateto,'geocode'=>$locationgeo,'result_type'=>$searchtype));
      return $tweets;
    }
    public function getLongLat($query)
    {
      $GEOdatax ="";
      $GEOdata="";
      if($query!=NULL)
      {
        $consumer="3Hsr7qk9HEYseF9bpJA07Go6H";
        $consumersecret="q1TjkWCHcPKrJn0YCmtKMwIHaXURI7ip3z5eDxDd4G0GxLsUOi";
        $accesstoken = "3803581341-FAlmx3CbE2amgcMyD2AkGYuLdRa7kZ3QcDSxj5a";
        $accesstokensecret = "1JtROKIjMaIhP6IJGMPGbTyRTF9WMyq1RGclpdvcKzZWD";
        $twitter= new TwitterOAuth($consumer,$consumersecret,$accesstoken,$accesstokensecret);
        $url="geo/search";
        $GEOquery = $twitter->get($url, array('query'=>$query,'granularity'=>'city','max_results'=>'1'));
        foreach ($GEOquery->result->places as $GEOresult) {
          $GEOdata = $GEOresult->centroid;
          $GEOtype = $GEOresult->place_type;
        }
        if($GEOdata!=NULL)
        {
          $GEOdatax = implode(',', $GEOdata);
          if($GEOtype=="country"){
            $radius=',479km';
          }
          else{
            $radius=',35mi';
          }
          $GEOdatax = $GEOdatax.$radius;
        }
      }
      return $GEOdatax;
    }

    public function searchportals($query,$urls)
    {
      $results=array();
      foreach ($urls as $url) {
        array_push($results,$this->searchportal($query,$url));
      }
      return $results;
    }

    public function searchportal($query,$url)
    {
      $results=array();
      // URL to crawl
      $tempurl="";
      switch ($url) {
        case "http://www.klix.ba":
            $results=$this->searchklix($query,$url);
            break;
        case "http://www.avaz.ba":
            $links = $this->searchavaz($query,$url);
            $results=$links;
            break;
        case "http://www.nezavisne.com/":
            $results= $this->searchnezavisne($query,$url);
            $this->portalcounter['nezavisne'] += (count($results)-1);
            break;
          }

      // At the end, after the process is finished, we print a short
      // report (see method getProcessReport() for more information)
      //$report = $crawler->getProcessReport();
      //echo "Links followed: ".$report->links_followed;
      //echo "Process runtime: ".$report->process_runtime." sec";

      //$this->portalcounter['avaz'] += $temp_counter['avaz'];
      //$this->portalcounter['ekskluziva'] += $temp_counter['ekskluziva'];

      return $results;
    }

    public function searchnezavisne($query,$url)
    {
      $crawler = new PHPCrawler();
      $crawler->setURL($url);
      $crawler->setquery($query);
      $crawler->addContentTypeReceiveRule("#text/html#");
      $crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i");
      $crawler->enableCookieHandling(true);
      // Set the traffic-limit to 1 MB (in bytes,
      $crawler->setTrafficLimit(4000 * 1024);
      // Thats enough, now here we go
      $crawler->go();
      //$report = $crawler->getProcessReport();
      //echo "Links followed: ".$report->links_followed;
      //echo "Process runtime: ".$report->process_runtime." sec";
      //$this->portalcounter['nezavisne']=$crawler->portalcounter['nezavisne'];
      return $crawler->retreiveresults();
    }

    public function searchklix($query,$url)
    {
      $tempurl=$url."/pretraga?pojam=".$query;
      $crawler = new PHPCrawler();
      $crawler->setURL($tempurl);
      $crawler->setCrawlingDepthLimit(0);
      $crawler->enableCookieHandling(true);
      $crawler->setTrafficLimit(1000 * 1024);
      $crawler->go();
      $doc = new \DOMDocument();
      libxml_use_internal_errors(true);
      $doc->loadHTML($crawler->retreiveresults());
      preg_match('#<span>[\d]+ [\w]+</span>#',$crawler->retreiveresults(),$mentions);
      preg_match('#[\d]+#',$mentions[0],$mentions);
      $this->portalcounter['klix'] += $mentions[0];
      //var_dump($this->portalcounter['klix']);
      //preg_match('/[\d]+/',$crawler->retreiveresults(),$mentions);
      $xpath = new \DOMXpath($doc);
      $articles = $xpath->query('//div[@class="news-line row"]');
      // all links in .news-line row
      $links = array();
      foreach($articles as $container) {
        $arr = $container->getElementsByTagName("a");
        foreach($arr as $item) {
          $href =  $item->getAttribute("href");
          $href="http://www.klix.ba".$href;
          $textcode=$doc->saveHTML($item);
          preg_match('/<h1>(.*?)<\/h1>/s', $textcode, $text);
          $links[] = array(
            'url' => $href,
            'title' => $text[1],
            'desc' => ""
          );
        }
      }
      return $links;
    }


    public function searchavaz($query,$url)
    {
      $links=array();
      $tempurl=$url."/pretraga/page:1?keyword=".$query;
      $tempdoc=file_get_contents($tempurl);
      $doc = new \DOMDocument();
      libxml_use_internal_errors(true);
      $doc->loadHTML($tempdoc);
      $xpath = new \DOMXpath($doc);
      $articles = $xpath->query('//article[@class="preview hybrid"]');
      // all links in .news-line row
        foreach($articles as $container) {
          $arr = $container->getElementsByTagName("a");
          $href =  $arr[2]->getAttribute("href");
          $href="http://www.avaz.ba".$href;
          $textcode=$doc->saveHTML($container);
          preg_match('/<p>(.*?)<\/p>/s', $textcode, $desc);
          preg_match('/<h2>(.*?)<\/h2>/s', $textcode, $title);
          $title[1]=strip_tags($title[1]);
          $templinks[] = array(
            'url' => $href,
            'title' => $title[1],
            'desc' => $desc[1]
          );
        }
      $this->portalcounter['avaz'] += count($templinks);

      //This function calculates the number of result pages in the search by using binary search technique
      $x=50; $min=2; $max=100; $numberofpages=1;
      while(TRUE)
      {
        $tempurl=$url."/pretraga/page:".$x."?keyword=".$query;
        if (@file_get_contents($tempurl))
        {
          $min=$x;
          $x=floor(($max+$x)/2);
        }
        else {
          $max=$x;
          $x=floor(($min+$x)/2);
        }
        if($min==$max || ($max-$min)<=1)
        {
          $numberofpages=$x;
          break;
        }
      }

      $this->portalcounter['avaz'] += (20*$numberofpages);

      return $templinks;
    }

    public function display(Request $request)
    {
      //$this->validate($request, [
        //'datefrom' => 'date_format:"Y-m-d"',
        //'dateto' => 'date_format:"Y-m-d"|after:datefrom',
      //]);
      $tweets="";
      $tweetsinfo="";
      $portalsmentions="";
      $trends="Nothing until now";
      $keyword = $request->input('keyword');
      $searchtype = $request->input('searchtype');
      $datefrom = $request->input('datefrom');
      $dateto = $request->input('dateto');
      $social1 = $request->input('social1');
      $social2 = $request->input('social2');
      $website1 = $request->input('website1');
      $website2 = $request->input('website2');
      $website3 = $request->input('website3');
      $trends1 = $request->input('trends1');
      $location = $request->input('location');
      //converting location to longitude and latitude for twitter search
      $locationgeo = $this->getLongLat($request->input('location'));


      if($keyword!=NULL && $social1!=NULL)
      {
      $tweets=$this->twittersearch($keyword,$dateto,$locationgeo,$searchtype);
      $tweetsinfo=$this->tweetsinf($tweets);
      }

      $urls=array();

      if($website1!=NULL)
      array_push($urls,$website1);
      if($website2!=NULL)
      array_push($urls,$website2);
      if($website3!=NULL)
      array_push($urls,$website3);

      if($urls!=NULL && $keyword!=NULL)
      {
        $portalsmentions=$this->searchportals($keyword,$urls);
      }
      $data=array('keyword'=>$keyword,'searchtype'=>$searchtype, 'datefrom'=>$datefrom,'dateto'=>$dateto, 'location'=>$location,'social1'=>$social1,'social2'=>$social2,'website1'=>$website1,'website2'=>$website2,'trends1'=>$trends1,'tweets'=>$tweets,'tweets_info'=>$tweetsinfo,'trends'=>$trends,
      'locationgeo'=>$locationgeo,'portalsresults'=>$portalsmentions,'website3'=>$website3,'portalcounter'=>$this->portalcounter);
      return view('results',$data);
    }


}
