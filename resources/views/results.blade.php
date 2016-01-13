@extends('search')

@section('title')
SocialFootprint
@stop

@section('trendingportals')
<h2> Please enter a keyword to search ...</h2>
<h2>{{ $locationgeo }}</h2>
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
<h1 style="text-align:center;"> Summary </h1>
<ul style="margin-left:20%;" class="nav nav-pills" role="tablist">
  <li role="presentation" class="active"><a href="#">Retweets and Shares <span class="badge">{{$tweets_info['0']}}</span></a></li>
  <li role="presentation" class="active"><a href="#">Favourites and Likes <span class="badge">{{$tweets_info['1']}}</span></a></li>
  <li role="presentation" class="active"><a href="#">Results found <span class="badge">{{$tweets_info['2']}}</span></a></li>
</ul>
</div>
<h1 style="text-align:center;"> Statuses </h1>
{{--<h3>{{$tweets->search_metadata->refresh_url}}</h3>--}}
@foreach ($tweets->statuses as $tweet)
    <p><img src="{{ asset('img/t.png') }}" width=20px>&nbsp;&nbsp;<span style="color:#A62400">{{ $tweet->user->name }}</span>: {{ $tweet->text }}</p>
@endforeach
@endif
@stop
