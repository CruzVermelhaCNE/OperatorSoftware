@extends('layouts/panel')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10">
    <div class="row">
        <div class="col-4 offset-4">
            <h1 class="text-center">Mudar Password</h1>
            <div class="text-center">
                <form action="{{ route('panel.change_password') }}" method="POST">
                    @csrf
                    <label for="inputPassword" class="sr-only">Password Atual</label>
                    <input type="password" id="inputCurrentPassword" class="form-control" placeholder="Password Atual" required="" name="current_password">
                    <label for="inputPassword" class="sr-only">Password Nova</label>
                    <input type="password" id="inputNewPassword" class="form-control" placeholder="Password Nova" required="" name="new_password">
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Mudar</button>
                </form>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
