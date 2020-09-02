@extends('salop/layouts/bootstrap')

@section('pageTitle', 'Login')

@section('style')
<style>
    body {
        display: -ms-flexbox;
        display: -webkit-box;
        display: flex;
        -ms-flex-align: center;
        -ms-flex-pack: center;
        -webkit-box-align: center;
        align-items: center;
        -webkit-box-pack: center;
        justify-content: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
    }
</style>
@endsection

@section('body')
<div class="text-center">

    <div class="form-signin">
        <img class="mb-4" src="/img/CNE.png" alt="Coordenação Nacional de Emergência - Cruz Vermelha Portuguesa"
            width="150" height="150">
        <a href="{{route('auth.microsoft')}}"><img src="/img/signinms.png" /></a>
    </div>
</div>
@endsection