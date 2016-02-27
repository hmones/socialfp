@extends('search')

@section('title')
SocialFootprint
@stop

@section('trendingportals')
@if($portals==NULL)
<p align=center><span class="glyphicon glyphicon-check" style="font-size:60px;text-align:center;color:#2E86BC;"></span></p>
<h3 align="center"> Please select trending portals' checkbox first to perform search</h3>
@elseif($keyword==NULL)
<p align=center><span class="glyphicon glyphicon-alert" style="font-size:60px;text-align:center;color:#2E86BC;"></span></p>
<h3 align="center"> Please type a valid keyword to search portals</h3>
@elseif($portalsresults==NULL)
<p align=center><span class="glyphicon glyphicon-keyword" style="font-size:60px;text-align:center;color:#2E86BC;"></span></p>
<h3 align="center">There are no matches</h3>
@else

<div style="display:block;">
<h1 style="text-align:center;font-family: 'Lobster', cursive;">Statistics for "{{$keyword}}"</h1>


<div class="col-md-3"><p align=center><span class="glyphicon glyphicon-thumbs-up" style="font-size:50px;color:#2E86BC;"></span></br><span class="badge" style="font-size:30px;">{{$social_stats["fb_likes"]}} </span></br>Facebook Likes </p></div>
<div class="col-md-3"><p align=center><span class="glyphicon glyphicon-share-alt" style="font-size:50px;color:#2E86BC;"></span></br><span class="badge" style="font-size:30px;">{{$social_stats["fb_shares"]}}</span></br> Facebook Shares </p></div>
<div class="col-md-3"><p align=center><span class="glyphicon glyphicon-comment" style="font-size:50px;color:#2E86BC;"></span></br><span class="badge" style="font-size:30px;">{{$social_stats["fb_comments"]}}</span></br> Total Comments </p></div>
<div class="col-md-3"><p align=center><span class="glyphicon glyphicon-plus" style="font-size:50px;color:#2E86BC;"></span></br><span class="badge" style="font-size:30px;">{{$social_stats["gp_shares"]}}</span></br> Google+ Shares </p></div>
<div class="col-md-12"><p align=center><span class="glyphicon glyphicon-globe" style="font-size:50px;color:#2E86BC;"></span></br><span class="badge" style="font-size:30px;">{{$social_stats["total_shares"]}}</span></br> Total Social Media Shares </p></div>

</div>
</br>
</br>

<div style='col-md-12 margin-left:10px'>
</br>
  <h2 style="font-family: 'Lobster', cursive;">Top results sorted by socialmedia shares (Total: {{$portalsresults->count()}})</h2>
@foreach ($portalsresults as $portalresult)
  @if($portalresult!=NULL)
    <a href='{{ $portalresult->url}}'> <h4>{{ $portalresult->page_title }}</h4></a>
    <p style='color:green;'>{{ $portalresult->url }}</p>
    <p>{{$portalresult->description}}</p>
    <hr style='color:border-top: 1px solid #aaaaaa;'>
  @endif
@endforeach
</div>
@endif
@stop

@section('googletrends')
@if($trends1==NULL)
<p align=center><span class="glyphicon glyphicon-check" style="font-size:60px;text-align:center;color:#2E86BC;"></span></p>
  <h3 align="center"> Please select GoogleTrends' checkbox first to perform search </h3>
@elseif($keyword==NULL)
  <p align=center><span class="glyphicon glyphicon-alert" style="font-size:60px;text-align:center;color:#2E86BC;"></span></p>
    <h3 align="center"> Please type a valid keyword to display Google Trends </h3>
@else
<div style="display:block;margin-left: 20%;">
<h2>Interest in the Keyword over time</h2>
  <script type="text/javascript" src="//www.google.com/trends/embed.js?hl=en-US&q={{ $keyword }}&cmpt=q&tz=Etc/GMT-1&tz=Etc/GMT-1&content=1&cid=TIMESERIES_GRAPH_0&export=5&w=500&h=330"></script>
</div>
<div style="display:block;margin-left: 20%;">
<h2>Interest in the Keyword over countries/regions</h2>
<script style="position: absolute;top: 50%;left: 50%;margin-top: -50px; margin-left: -50px;" type="text/javascript" src="//www.google.com/trends/embed.js?hl=en-US&q={{ $keyword }}&cmpt=q&tz=Etc/GMT-1&tz=Etc/GMT-1&content=1&cid=GEO_TABLE_0_0&export=5&w=500&h=450"></script>
<h2>Related searches</h2>
<script style="position: absolute;top: 50%;left: 50%;margin-top: -50px; margin-left: -50px;" type="text/javascript" src="//www.google.com/trends/embed.js?hl=en-US&q={{ $keyword }}&cmpt=q&tz=Etc/GMT-1&tz=Etc/GMT-1&content=1&cid=TOP_QUERIES_0_0&export=5&w=500&h=450"></script>
</div>
@endif
@stop

@section('socialmedia')

@if($social1==NULL)
<p align=center><span class="glyphicon glyphicon-check" style="font-size:60px;text-align:center;color:#2E86BC;"></span></p>
  <h3 align="center"> Please select Twitter's checkbox first to perform search</h3>
@elseif($keyword==NULL)
<p align=center><span class="glyphicon glyphicon-alert" style="font-size:60px;text-align:center;color:#2E86BC;"></span></p>
  <h3 align="center"> Please type a valid keyword to display Twitter results </h3>
@elseif($tweets==NULL)
<p align=center><span class="glyphicon glyphicon-search" style="font-size:60px;text-align:center;color:#2E86BC;"></span></p>
  <h3 align="center"> There are no tweets matching your search settings </h3>
@else
<div style="display:block;">
<h1 style="text-align:center;font-family: 'Lobster', cursive;"> Statistics for "{{$keyword}}"</h1>

<div class="col-md-4"><p align=center><span class="glyphicon glyphicon-retweet" style="font-size:50px;color:#2E86BC;"></span></br><span class="badge" style="font-size:30px;">{{$tweets_info['0']}} </span></br>Retweets and Shares </p></div>
<div class="col-md-4"><p align=center><span class="glyphicon glyphicon-heart-empty" style="font-size:50px;color:#2E86BC;"></span></br><span class="badge" style="font-size:30px;">{{$tweets_info['1']}}</span></br> Favourites and Likes </p></div>
<div class="col-md-4"><p align=center><span class="glyphicon glyphicon-flash" style="font-size:50px;color:#2E86BC;"></span></br><span class="badge" style="font-size:30px;">{{$tweets_info['3']}}</span></br> Mentions by influential users </p></div>

</div>
<h2 style="font-family: 'Lobster', cursive;"> Statuses </h2>
{{--<h3>{{$tweets->search_metadata->refresh_url}}</h3>--}}
@foreach ($tweets->statuses as $tweet)
    <p><img src="{{ asset('img/t.png') }}" width=20px>&nbsp;&nbsp;<span style="color:#A62400">{{ $tweet->user->name }}</span>: {{ $tweet->text }}</p>
@endforeach
 @endif

@stop
