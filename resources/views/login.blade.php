@extends('layouts/bootstrap')

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

    <form class="form-signin" action="{{ route('login') }}" method="POST">
        @csrf
        <img class="mb-4" src="/img/CNE.png" alt="Coordenação Nacional de Emergência - Cruz Vermelha Portuguesa"
            width="150" height="150">
        <h1 class="h3 mb-3 font-weight-normal">Por favor inicie sessão</h1>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <label for="inputEmail" class="sr-only">Endereço de Email</label>
        <input type="email" id="inputEmail" class="form-control" placeholder="Endereço de Email" required=""
            autofocus="" name="email">
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required=""
            name="password">
        <input class="form-check-input" type="checkbox" name="remember" id="remember"
            {{ old('remember') ? 'checked' : '' }}>

        <label class="form-check-label" for="remember">
            Lembrar-me
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
    </form>
</div>
@endsection