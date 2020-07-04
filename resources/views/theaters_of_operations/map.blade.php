@extends('theaters_of_operations/layouts/panel')

@section('pageTitle', 'Mapa')

@section('style')
@parent
<link href="https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.css" rel="stylesheet" />
<style>
    #map {
        height: 100vh;
    }
    /*#rightsidebar {
        float: right;
        width: calc(100% - 5px);
        background-color: #343a40;
        height: 100vh;
    }
    
    #footerbar {
        margin-top: 5px !important;
        height: calc(35vh - 5px);
        background-color:#343a40;
    }*/
</style>
@endsection

@section('content')
<div class="row px-0 py-0 mx-0 my-0">
    <div class="col-9 px-0 py-0">
        <div id="map"></div>
    </div>
    <!--<div class="col-9 px-0 py-0">
        <div id="map"></div>
        <div id="footerbar">
        </div>
    </div>
    <div class="col-3 px-0 py-0">
        <div id="rightsidebar">
        </div>
    </div>-->
</div>
@endsection

@section('javascript')
@parent
<script src="{{ mix('js/map.js') }}"></script>
<script>
    let map = new Map(0, 'map', 6, 40.216771, -8.436872);
</script>
@endsection