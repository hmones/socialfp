@extends('default')

@section('title')
Social Footprint
@stop

@section('content')
    <div class="row" style="color:white;">
      <form class="screen-centered" action="search" method="get">
        <h2 align="center"> SocialFootprint </h2>
      </br>
        <div class="input-group">
        <input type="text" name="keyword" class="form-control" placeholder="Search for...">
        <span class="input-group-btn">
          <button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-search"></span></button>
        </span>
        </div>
        <h5 align="left">Where to search:</h5>
        <hr>
        <div class="row">
          <div class="col-md-4">
            <h5> Social Media: </h5>
            <input type="checkbox" name="social1" value="http://www.twitter.com" ><label>&nbsp;Twitter&nbsp;</label>
            <br>
            <input type="checkbox" name="social2" value="http://www.facebook.com" ><label>&nbsp;Facebook&nbsp;</label>
          </div>
          <div class="col-md-4">
            <h5> Trending Portals </h5>
            <input type="checkbox" name="website1" value="http://www.zastone.ba" ><label>&nbsp; Zastone&nbsp;</label>
            <br>
            <input type="checkbox" name="website2" value="http://point.zastone.ba" ><label>&nbsp; Point&nbsp;</label>
          </div>
          <div class="col-md-4">
            <h5> Search Engines </h5>
            <input type="checkbox" name="trends1" value="http://trends.google.com" ><label>&nbsp; GTrends&nbsp;</label>
          </div>
        </div>
      </form>
    </div>
@stop
