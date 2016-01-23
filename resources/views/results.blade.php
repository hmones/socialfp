@extends('search')

@section('title')
SocialFootprint
@stop

@section('trendingportals')
@if($website1==NULL || $keyword==NULL)
<h2> Please enter a keyword to search ...</h2>
@elseif($portalsresults==NULL)
<h2>There are no matches</h2>
@else
<div style="display:block;">
<h2 style="text-align:center;">Summary</h2>
<ul style="margin-left:10%;" class="nav nav-pills" role="tablist">
  <li role="presentation" class="active"><a href="#">Total Mentions <span class="badge">{{count($portalsresults)}}</span></a></li>
  <li role="presentation" class="active"><a href="#">Klix mentions <span class="badge">{{$portalcounter["klix"]}}</span></a></li>
  <li role="presentation" class="active"><a href="#">Avaz mentions <span class="badge">{{$portalcounter["avaz"]}}</span></a></li>
  <li role="presentation" class="active"><a href="#">Ekskluziva mentions <span class="badge">{{$portalcounter["ekskluziva"]}}</span></a></li>
</ul>
</div>

<h2>Top results</h2>
<div style='margin-left:10px'>
@foreach ($portalsresults as $result)
<a href='{{ $result["url"] }}'> <h4>{{ $result["title"] }}</h4></a>
<p style='color:green;'>{{ $result["url"] }}</p>
<p>{{$result["desc"]}}</p>
<hr style='color:border-top: 1px solid #aaaaaa;'>
@endforeach
</div>

@endif
@stop

@section('googletrends')
@if($trends1==NULL)
  <h2> Please enter a keyword to search ... </h2>
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

@if(($tweets==NULL)||$social1==NULL||$keyword==NULL)
  <h2> Please enter a keyword to search ... </h2>
@else
<div style="display:block;">
<h2 style="text-align:center;"> Summary </h2>

<div style="text-align:center;">
<span class="glyphicon glyphicon-retweet" style="font-size:60px;text-align:center;color:#3C763D;">&nbsp;&nbsp;&nbsp;</span>
<span class="glyphicon glyphicon-heart-empty" style="font-size:60px;text-align:center;color:#A94442;">&nbsp;&nbsp;&nbsp;</span>
<span class="glyphicon glyphicon-flash" style="font-size:60px;text-align:center;color:#337AB7;"></span>
</div>
<br>

<ul style="margin-left:10%;" class="nav nav-pills" role="tablist">
  <li role="presentation" class="active"><a href="#">Retweets and Shares <span class="badge">{{$tweets_info['0']}}</span></a></li>
  <li role="presentation" class="active"><a href="#">Favourites and Likes <span class="badge">{{$tweets_info['1']}}</span></a></li>
  <li role="presentation" class="active"><a href="#">Mentions by influential users <span class="badge">{{$tweets_info['3']}}</span></a></li>
</ul>
</div>
<h2> Statuses </h2>
{{--<h3>{{$tweets->search_metadata->refresh_url}}</h3>--}}
@foreach ($tweets->statuses as $tweet)
    <p><img src="{{ asset('img/t.png') }}" width=20px>&nbsp;&nbsp;<span style="color:#A62400">{{ $tweet->user->name }}</span>: {{ $tweet->text }}</p>
@endforeach
 @endif

@stop
