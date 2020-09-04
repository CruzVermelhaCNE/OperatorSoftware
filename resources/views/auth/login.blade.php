@extends('auth/layouts/bootstrap')

@section('pageTitle', 'Autenticação')

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

@section('app')
    <Login microsoft-route="{{ route('auth.microsoft') }}"></Login>
@endsection