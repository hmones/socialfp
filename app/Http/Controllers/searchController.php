<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Browser\Casper;

class searchController extends Controller
{

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

    public function gtrendsdisplay($query)
    {
      $output=$query;
      $casper = new Casper();

      $casper->setOptions(array(
          'ignore-ssl-errors' => 'yes'
      ));
      $casper->setUserAgent('Mozilla/4.0 (comptible; MSIE 6.0; Windows NT 5.1)');
      $url='https://www.google.com/trends/explore#q='.$query;

      $casper->start($url);


      $casper->wait(5000);
      $casper->captureSelector('#reportMain', $output);
      $casper->capturePage('pagesample.png');

      $casper->run();
      $regex = '#\<div id="reportMain"\>(.+?)\<\/div\>#s';
      preg_match($regex, $output, $matches);

      return $output;
    }

    public function display(Request $request)
    {
      $this->validate($request, [
        'datefrom' => 'date_format:"Y-m-d"',
        'dateto' => 'date_format:"Y-m-d"|after:datefrom',
      ]);
      $tweets="";
      $tweetsinfo="";
      $trends="Nothing until now";
      $keyword = $request->input('keyword');
      $searchtype = $request->input('searchtype');
      $datefrom = $request->input('datefrom');
      $dateto = $request->input('dateto');
      $social1 = $request->input('social1');
      $social2 = $request->input('social2');
      $website1 = $request->input('website1');
      $website2 = $request->input('website2');
      $trends1 = $request->input('trends1');
      $location = $request->input('location');
      //converting location to longitude and latitude for twitter search
      $locationgeo = $this->getLongLat($request->input('location'));


      if($keyword!=NULL && $social1!=NULL)
      {
      $tweets=$this->twittersearch($keyword,$dateto,$locationgeo,$searchtype);
      $tweetsinfo=$this->tweetsinf($tweets);
      }
      $data=array('keyword'=>$keyword,'searchtype'=>$searchtype, 'datefrom'=>$datefrom,'dateto'=>$dateto, 'location'=>$location,'social1'=>$social1,'social2'=>$social2,'website1'=>$website1,'website2'=>$website2,'trends1'=>$trends1,'tweets'=>$tweets,'tweets_info'=>$tweetsinfo,'trends'=>$trends,'locationgeo'=>$locationgeo);
      return view('results',$data);
    }


}
