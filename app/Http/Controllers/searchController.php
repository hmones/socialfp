<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Haythameyd\PHPCrawl\PHPCrawler;

class searchController extends Controller
{

    protected $portalcounter=array();

    public function tweetsinf($tweets)
    {
      $count=0;
      $retweets=0;
      $likes=0;
      foreach($tweets->statuses as $obj){
         $retweets=$retweets+$obj->retweet_count;
         $likes=$likes+$obj->favorite_count;
         $count=$count+1;
      }
      $data=array($likes,$retweets,$count);
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

      $crawler = new PHPCrawler();
      foreach ($urls as $url) {
      // URL to crawl
      $crawler->setURL($url);

      $crawler->setquery($query);

      // Only receive content of files with content-type "text/html"
      $crawler->addContentTypeReceiveRule("#text/html#");

      // Ignore links to pictures, dont even request pictures
      $crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i");

      // Store and send cookie-data like a browser does
      $crawler->enableCookieHandling(true);

      // Set the traffic-limit to 1 MB (in bytes,
      // for testing we dont want to "suck" the whole site)
      $crawler->setTrafficLimit(1000 * 1024);
      // Thats enough, now here we go
      $crawler->go();

      // At the end, after the process is finished, we print a short
      // report (see method getProcessReport() for more information)
      $report = $crawler->getProcessReport();

      echo "Links followed: ".$report->links_followed;
      echo "Process runtime: ".$report->process_runtime." sec";
      }
      $this->portalcounter=$crawler->retreiveportalmentions();
      return $crawler->retreiveresults();
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
