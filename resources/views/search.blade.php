@extends('default')

@section('title')
Social Footprint
@stop

@section('content')
{{--
 @if (count($errors) > 0)
    <div style="margin-top:50px;" class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
 @endif
--}}
<div class="row" style="margin-top:50px;margin-left:10px;">
  <!--
  SEARCH FILTER SECTION
  This section will contain all the websites to be searched, twitter settings
  and all the other filters that can be applied to a specific searchsite
  -->
  <div class"col-md-4" style="color:white;">
    <form class="navbar-form navbar-left" role="search" method="get" action="search">
      <div class="form-group">
        <h3>Search Keyword</h3>
        <input type="text" name="keyword" class="form-control" value="{{$keyword}}">
      </br>
      <h4>Filters:</h4>
        <div class="well col-md-12" style="color:black;">
          <h5>Social Media:</h5>
          <input type="checkbox" name="social1" value="http://www.twitter.com" @if($social1!=NULL) checked @endif ><label>&nbsp;Twitter&nbsp;</label>
          {{--</br>
          <input type="checkbox" name="social2" value="http://www.facebook.com" @if($social2!=NULL) checked @endif ><label>&nbsp;Facebook&nbsp;</label>
          --}}
          </br>
          <select class="form-control" name="searchtype">
            <option value="recent">Recent results</option>
            <option value="popular">Popular results</option>
            <option value="mixed">Mixed results</option>
          </select>
          </br>
          <input type="text" name="datefrom" class="form-control" placeholder="From (yyyy/mm/dd)" @if($datefrom!=NULL) value={{$datefrom}} @endif >
          </br>
          <input type="text" name="dateto" class="form-control" placeholder="To (yyyy/mm/dd)" @if($dateto!=NULL) value={{$dateto}} @endif >
          </br>
            <input type="text" name="location" class="form-control" placeholder="Select Location" @if($location!=NULL) value="{{$location}}" @endif >
          </br>
          <h5>Trending Portals</h5>
          <input type="checkbox" name="portals" value="yes" @if($portals!=NULL) checked @endif ><label>&nbsp; Portals&nbsp;</label>
          </br>
          <h5>Search Engines</h5>
          <input type="checkbox" name="trends1" value="http://trends.google.com" @if($trends1!=NULL) checked @endif ><label>&nbsp; Google Trends&nbsp;</label>
          <hr style="border-top:1px solid #972002;">
          <button type="submit" class="btn btn-success">Apply Criteria</button>
        </div>
      </div>
    </form>
  </div>


  <!-- SEARCH RESULTS DISPLAY
  This Area searches a specific website for
  the keywords specified
  It cascades the results based on what has been searched
  It displays only the results with the links
  It uses a function search site from the search php file

  -->
  <div class="col-md-8" style="background-color: rgba(255, 255, 255, 0.85);border-radius: 10px;margin-top:20px;">
    <div>

      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#socialmedia" aria-controls="home" role="tab" data-toggle="tab">Social Media</a></li>
        <li role="presentation"><a href="#trendingportals" aria-controls="profile" role="tab" data-toggle="tab">Trending Portals</a></li>
        <li role="presentation"><a href="#googletrends" aria-controls="messages" role="tab" data-toggle="tab">Google Trends</a></li>
      </ul>

      <!-- Tab panes -->


      <div class="well tab-content">
        <div role="tabpanel" class="tab-pane active" id="socialmedia">
          @yield('socialmedia')
        </div>
        <div role="tabpanel" class="tab-pane" id="trendingportals">
          @yield('trendingportals')
        </div>
        <div role="tabpanel" class="tab-pane" id="googletrends">
          @yield('googletrends')
        </div>
      </div>

    </div>
  </div>
</div>
@stop
