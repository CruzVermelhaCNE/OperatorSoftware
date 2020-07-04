@extends('theaters_of_operations/layouts/bootstrap')

@section('style')
<link rel="stylesheet" href="{{ mix('css/theaters_of_operations.css') }}">

@endsection

@section('body')
<div class="container-fluid">
    <div class="row" style="height: 100%">
        @include('theaters_of_operations/layouts/sidebar')
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script src="{{ mix('js/dashboard.js') }}"></script>
<script>
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
</script>
@endsection