@extends('layouts/panel')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 panel">
    <a href="#" class="btn btn-primary">Abrir Porta</a>
    <div class="embed-responsive embed-responsive-16by9">
    <video class="embed-responsive-item" autoplay autobuffer src="{{ env('DOOR_OPENER_STREAM') }}"></video>
    </div>
</main>
@endsection

@section('javascript')
@parent

@endsection