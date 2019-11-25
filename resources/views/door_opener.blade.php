@extends('layouts/panel')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 panel">
    <div class="container">
        <a href="#" class="btn btn-primary">Abrir Porta</a>
        <div class="embed-responsive embed-responsive-16by9">
            
        </div>
    </div>
</main>
@endsection

@section('javascript')
@parent
<script type="text/javascript">
$(document).ready(() => {
    $(document).ready(() => {
        $.get("/data/gds_video_url", function(url, status) {
            $('#door_opener_video').append('<video class="embed-responsive-item" id="door_opener_video" autoplay autobuffer src="'+url+'"></video>');
        });
    });
});
</script>
@endsection