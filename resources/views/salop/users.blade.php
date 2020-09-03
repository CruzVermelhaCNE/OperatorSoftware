@extends('salop/layouts/panel')

@section('pageTitle', 'Utilizadores')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10">
    <h1 class="text-center">Utilizadores</h1>
    <table id="users" class="table" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Cargos</th>
                <th>Extensões</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->ranks }}</td>
                <td>{{ $user->all_extensions }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-6">
            <h5>Editar Permissões</h5>
            <form method="POST" action="{{route('salop.users.editPermissions')}}">
                @csrf
                <select class="custom-select" name="user">
                    @foreach ($users as $user)
                    <option value="{{$user->id}}">{{$user->name}} - {{$user->email}}</option>
                    @endforeach
                </select>

                <select class="custom-select" multiple name="permissions[]">
                    <option value="1">Administrador</option>
                    <option value="2">Gestor</option>
                    <option value="4">GOI</option>
                    <option value="5">SALOP</option>
                    <option value="none">Nenhuma</option>
                </select>
                <button class="btn btn-primary" type="submit">Editar</button>
            </form>
        </div>
        <div class="col-6">
            <h5>Editar Extensões</h5>
            <form method="POST" action="{{route('salop.users.editExtensions')}}">
                @csrf
                <select class="custom-select" name="user">
                    @foreach ($users as $user)
                    <option value="{{$user->id}}">{{$user->name}} - {{$user->email}}</option>
                    @endforeach
                </select>

                <select class="custom-select" multiple name="extensions[]">
                        @foreach ($extensions as $extension)
                        <option value="{{$extension->id}}">{{$extension->number}}</option>
                        @endforeach
                        <option value="none">Nenhuma</option>
                </select>
                <button class="btn btn-primary" type="submit">Editar</button>
            </form>
        </div>
    </div>

</main>
@endsection

@section('javascript')
@parent
<script>
    $(document).ready(() => {
        var table = $('#users').DataTable();
    });
</script>
@endsection