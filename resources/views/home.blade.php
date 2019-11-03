@extends('layouts/panel')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 panel">
    <iframe src="{{ env('FOP2_ADDRESS') }}"></iframe>
</main>
@endsection