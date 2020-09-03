@extends('salop/layouts/panel')

@section('pageTitle', 'Inicio')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10">
    <h3>Bem-vindo {{Auth::user()->name}}</h3> 
</main>
@endsection