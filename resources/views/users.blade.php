@extends('layouts/panel')

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