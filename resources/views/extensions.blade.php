@extends('layouts/panel')

@section('pageTitle', 'Extensões')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10">
    <h1 class="text-center">Utilizadores</h1>
    <table id="extensions" class="table" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Extensão</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($extensions as $extension)
            <tr>
                <td>{{ $extension->id }}</td>
                <td>{{ $extension->number }}</td>
                <td>{{ $extension->password }}</td>
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
        var table = $('#extensions').DataTable();
    });
</script>
@endsection