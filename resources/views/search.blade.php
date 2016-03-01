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
          <p><b>Date Settings:</b> <br>(Works only for Portals)</p>
          <input type="text" name="datefrom" class="form-control" placeholder="From (yyyy/mm/dd)" @if($datefrom!=NULL) value={{$datefrom}} @endif >
          </br>
          <input type="text" name="dateto" class="form-control" placeholder="To (yyyy/mm/dd)" @if($dateto!=NULL) value={{$dateto}} @endif >
          <br><br>
          <input type="checkbox" name="social1" value="http://www.twitter.com" @if($social1!=NULL) checked @endif ><label>&nbsp;Twitter&nbsp;</label>
          {{--</br>
          <input type="checkbox" name="social2" value="http://www.facebook.com" @if($social2!=NULL) checked @endif ><label>&nbsp;Facebook&nbsp;</label>
          --}}
          </br>
          <select class="form-control" name="searchtype">
            <option value="recent" @if($searchtype=="recent") selected="selected" @endif>Recent results</option>
            <option value="popular" @if($searchtype=="popular") selected="selected" @endif>Popular results</option>
            <option value="mixed" @if($searchtype=="mixed") selected="selected" @endif>Mixed results</option>
          </select>
          </br>
            <input type="text" name="location" class="form-control" placeholder="Type Location" @if($location!=NULL) value="{{$location}}" @endif >
          </br><br>
          <label>Bosnian Portals&nbsp;</label>
          <p style="font-size:12px;">Hold ctrl key to select multiple portals</p>
          
          <select name="portal[]" multiple class="portalselect">
            <option value="www.klix.ba" @if(in_array('www.klix.ba',$portal)) selected @endif>www.klix.ba</option>
            <option value="www.ekskluziva.ba" @if(in_array('www.ekskluziva.ba',$portal)) selected @endif>www.ekskluziva.ba</option>
            <option value="balkans.aljazeera.net"@if(in_array('balkans.aljazeera.net',$portal)) selected @endif>balkans.aljazeera.net</option>
            <option value="www.radiosarajevo.ba"@if(in_array('www.radiosarajevo.ba',$portal)) selected @endif>www.radiosarajevo.ba</option>
            <option value="www.nezavisne.com"@if(in_array('www.nezavisne.com',$portal)) selected @endif>www.nezavisne.com</option>
            <option value="www.bljesak.info"@if(in_array('www.bljesak.info',$portal)) selected @endif>www.bljesak.info</option>
            <option value="www.fokus.ba"@if(in_array('www.fokus.ba',$portal)) selected @endif>www.fokus.ba</option>
            <option value="www.sportsport.ba"@if(in_array('www.sportsport.ba',$portal)) selected @endif>www.sportsport.ba</option>
            <option value="www.novi.ba"@if(in_array('www.novi.ba',$portal)) selected @endif>www.novi.ba</option>
            <option value="www.vijesti.ba"@if(in_array('www.vijesti.ba',$portal)) selected @endif>www.vijesti.ba</option>
            <option value="www.depo.ba"@if(in_array('www.depo.ba',$portal)) selected @endif>www.depo.ba</option>
            <option value="www.source.ba"@if(in_array('www.source.ba',$portal)) selected @endif>www.source.ba</option>
            <option value="www.bh-index.com"@if(in_array('www.bh-index.com',$portal)) selected @endif>www.bh-index.com</option>
            <option value="www.oslobodjenje.ba"@if(in_array('www.oslobodjenje.ba',$portal)) selected @endif>www.oslobodjenje.ba</option>
            <option value="www.krajina.ba"@if(in_array('www.krajina.ba',$portal)) selected @endif>www.krajina.ba</option>
            <option value="www.haber.ba"@if(in_array('www.haber.ba',$portal)) selected @endif>www.haber.ba</option>
            <option value="www.buka.com"@if(in_array('www.buka.com',$portal)) selected @endif>www.buka.com</option>
            <option value="www.hayat.ba"@if(in_array('www.hayat.ba',$portal)) selected @endif>www.hayat.ba</option>
            <option value="ba.n1info.com"@if(in_array('ba.n1info.com',$portal)) selected @endif>ba.n1info.com</option>
            <option value="www.glassrpske.com"@if(in_array('www.glassrpske.com',$portal)) selected @endif>www.glassrpske.com</option>
            <option value="www.faktor.ba"@if(in_array('www.faktor.ba',$portal)) selected @endif>www.faktor.ba</option>
            <option value="www.cazin.net"@if(in_array('www.cazin.net',$portal)) selected @endif>www.cazin.net</option>
            <option value="www.biscani.net"@if(in_array('www.biscani.net',$portal)) selected @endif>www.biscani.net</option>
            <option value="www.vecernji.ba"@if(in_array('www.vecernji.ba',$portal)) selected @endif>www.vecernji.ba</option>
            <option value="www.avaz.ba"@if(in_array('www.avaz.ba',$portal)) selected @endif>www.avaz.ba</option>
            <option value="www.abc.ba"@if(in_array('www.abc.ba',$portal)) selected @endif>www.abc.ba</option>
          </select>
          </br>
        </br>
          <input type="checkbox" name="trends1" value="http://trends.google.com" @if($trends1!=NULL) checked @endif ><label>&nbsp; Google Trends&nbsp;</label>
          <hr style="border-top:1px solid #aeaeae;">
          <button type="submit" class="btn btn-success" style="position:relative;left:20%;">Apply Criteria</button>
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
  <div class="col-md-9" style="background-color: rgba(255, 255, 255, 0.85);border-radius: 10px;margin-top:20px;">
    <div>

      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist" style="background-color:#fff;">
        <li role="presentation"><a href="#socialmedia" aria-controls="home" role="tab" data-toggle="tab">Social Media</a></li>
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
