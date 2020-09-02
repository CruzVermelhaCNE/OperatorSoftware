@extends('layouts/panel')

@section('pageTitle', 'Chamadas Perdidas')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10">
    <h1 class="text-center">Chamadas Perdidas</h1>
    <h5 class="text-center">Últimos 7 Dias</h5>
    <table id="calls" class="table" style="width:100%">
        <thead>
            <tr>
                <th>Data</th>
                <th>Número</th>
                <th>Nome Interno</th>
                <th>Duração</th>
                <th>Número para qual foi ligado</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</main>
@endsection

@section('javascript')
@parent
<script>
    $(document).ready(() => {

        var table = $('#calls').DataTable( {
            ajax: {
                url: "{{ route('data.missed_calls') }}",
                timeout: 60000
            },
            "columns": [
                { "data": "calldate" },
                { "data": "src" },
                { "data": "clid" },
                { "data": "duration" },
                { "data": "did" }
            ],
            "order": [[0, 'desc']]
        });
        setInterval( function () {
            table.ajax.reload( null, false );
        }, 60000 );
    });
</script>
@endsection