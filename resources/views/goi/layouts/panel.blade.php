@extends('goi/layouts/bootstrap')

@section('style')
<link rel="stylesheet" href="{{ mix('css/theaters_of_operations.css') }}">

@endsection

@section('body')
<div class="container-fluid">
    <div class="row" style="height: 100%">
        @include('goi/layouts/sidebar')
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
</script>
@endsection