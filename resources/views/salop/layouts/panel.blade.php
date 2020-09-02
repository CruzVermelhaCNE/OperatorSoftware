@extends('salop/layouts/bootstrap')

@section('style')
    <link href="{{ mix('css/dashboard.css') }}" rel="stylesheet">
@endsection

@section('body')
    @include('salop/layouts/topbar')

    <div class="container-fluid" style="height: calc( 100% - 3rem);">
      <div class="row"  style="height: 100%">
        @include('salop/layouts/sidebar')

        @yield('content')
      </div>
    </div>
@endsection

@section('javascript')
    <script src="{{ mix('js/dashboard.js') }}"></script>
@endsection