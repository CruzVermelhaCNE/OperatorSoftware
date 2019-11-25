@extends('layouts/panel')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 panel" style="margin-top:1rem;">
    <div class="container">
        <a href="#" onclick="openDoor()" class="btn btn-primary">Abrir Porta</a>
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="{{ env('GDS3710_VIDEO') }}"></iframe>
        </div>
    </div>
</main>
@endsection

@section('javascript')
@parent
<script>
    function openDoor() {
        $.get("{{ route('actions.open_door')}}", function(data, status) {
            toastr.success('Porta Aberta', 'Video Porteiro')
        })
    }
</script>
@endsection