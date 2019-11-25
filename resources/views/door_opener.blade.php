@extends('layouts/panel')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 panel">
    <div class="container">
        <a href="#" class="btn btn-primary">Abrir Porta</a>
        <div class="embed-responsive embed-responsive-16by9">
            <video class="embed-responsive-item" id="door_opener_video" autoplay autobuffer src="http://{{ env('GDS3710_IP') }}/videoview.html"></video>
        </div>
    </div>
</main>
@endsection