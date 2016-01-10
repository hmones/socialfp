@extends('search')

@section('title')
Social Search Engine
@stop

@section('googletrends')
<script align="center" type="text/javascript" src="//www.google.com/trends/embed.js?hl=en-US&q={{ $keyword }}&cmpt=q&tz=Etc/GMT-1&tz=Etc/GMT-1&content=1&cid=TIMESERIES_GRAPH_0&export=5&w=500&h=330"></script>

<script type="text/javascript" src="//www.google.com/trends/embed.js?hl=en-US&q={{ $keyword }}&cmpt=q&tz=Etc/GMT-1&tz=Etc/GMT-1&content=1&cid=GEO_TABLE_0_0&export=5&w=500&h=330"></script>

<script type="text/javascript" src="//www.google.com/trends/embed.js?hl=en-US&q={{ $keyword }}&cmpt=q&tz=Etc/GMT-1&tz=Etc/GMT-1&content=1&cid=TOP_QUERIES_0_0&export=5&w=300&h=420"></script>
@stop

@section('socialmedia')

{{ var_dump($tweets) }}

@stop
