@extends('layouts/panel')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 panel">
    <div class="container">
        <a href="#" class="btn btn-primary">Abrir Porta</a>
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="{{ env('GDS3710_VIDEO') }}"></iframe>
        </div>
    </div>
</main>
@endsection