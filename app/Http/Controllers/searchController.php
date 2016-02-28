<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Haythameyd\PHPCrawl\PHPCrawler;
use App\results;

class searchController extends Controller
{

    protected $social_statistics=array('fb_likes'=>0,'fb_shares'=>0,'fb_comments'=>0,'gp_shares'=>0,'total_shares'=>0);

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

    public function update_social_count($social_stats)
    {
      global $social_statistics;
      $social_statistics['fb_likes']=$social_stats['fb_likes'];
      $social_statistics['fb_shares']=$social_stats['fb_shares'];
      $social_statistics['fb_comments']=$social_stats['fb_comments'];
      $social_statistics['gp_shares']=$social_stats['gp_shares'];
      $social_statistics['total_shares']=$social_stats['total_shares'];
    }

    public function searchportals ($keyword,$datefrom,$dateto)
    {
      $keyword='%'.$keyword.'%';
      if($datefrom==NULL || $dateto==NULL)
      {
        $search_items=results::where('content', 'LIKE', $keyword)->orderBy('total_shares','desc')->get(['url','portal', 'page_title','date','fb_likes','fb_shares','fb_comments','gp_shares','total_shares']);
      }else{
      $datefrom=preg_replace('#\/#','-',$datefrom);
      $dateto=preg_replace('#\/#','-',$dateto);
      $search_items=results::where('content', 'LIKE', $keyword)
            ->where(function ($query) use ($datefrom,$dateto)  {
                $query->whereBetween('date', [$datefrom, $dateto])
                      ->orWhere('date', '=', NULL);
            })->orderBy('total_shares','desc')->get(['url','portal', 'page_title','date','fb_likes','fb_shares','fb_comments','gp_shares','total_shares']);
          }
      $fb_likes=$search_items->sum('fb_likes');
      $fb_shares=$search_items->sum('fb_shares');
      $fb_comments=$search_items->sum('fb_comments');
      $gp_shares=$search_items->sum('gp_shares');
      $total_shares=$search_items->sum('total_shares');
      $social_stats=array('fb_likes'=>$fb_likes,'fb_shares'=>$fb_shares,'fb_comments'=>$fb_comments,'gp_shares'=>$gp_shares,'total_shares'=>$total_shares);
      $this->update_social_count($social_stats);
      return $search_items;
    }

    public function display(Request $request)
    {
      //$this->validate($request, [
        //'datefrom' => 'date_format:"Y-m-d"',
        //'dateto' => 'date_format:"Y-m-d"|after:datefrom',
      //]);
      global $social_statistics;
      $tweets="";
      $portalsmentions="";
      $tweetsinfo="";
      $trends="Nothing until now";
      $keyword = $request->input('keyword');
      $searchtype = $request->input('searchtype');
      $datefrom = $request->input('datefrom');
      $dateto = $request->input('dateto');
      $social1 = $request->input('social1');
      $social2 = $request->input('social2');
      $portals = $request->input('portals');
      $trends1 = $request->input('trends1');
      $location = $request->input('location');
      //converting location to longitude and latitude for twitter search
      $locationgeo = $this->getLongLat($request->input('location'));


      if($keyword!=NULL && $social1!=NULL)
      {
      $tweets=$this->twittersearch($keyword,$dateto,$locationgeo,$searchtype);
      $tweetsinfo=$this->tweetsinf($tweets);
      }

      // $urls=array();
      //
      // if($website1!=NULL)
      // array_push($urls,$website1);
      // if($website2!=NULL)
      // array_push($urls,$website2);
      // if($website3!=NULL)
      // array_push($urls,$website3);


      if($keyword!=NULL && $portals!=NULL)
      {
        $portalsmentions=$this->searchportals($keyword,$datefrom,$dateto);
      }
      $data=array('keyword'=>$keyword,'searchtype'=>$searchtype, 'datefrom'=>$datefrom,'dateto'=>$dateto, 'location'=>$location,'social1'=>$social1,'social2'=>$social2,'portals'=>$portals,'trends1'=>$trends1,'tweets'=>$tweets,'tweets_info'=>$tweetsinfo,'trends'=>$trends,
      'locationgeo'=>$locationgeo,'portalsresults'=>$portalsmentions,'social_stats'=>$social_statistics);
      return view('results',$data);
    }


}
