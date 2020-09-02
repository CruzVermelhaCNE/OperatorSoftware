@extends('goi/layouts/panel')

@section('pageTitle', 'Teatros de Operações')

@section('style')
@parent
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
<div class="container">
    <h2>Teatros de Operações - Ativos</h2>
    <table id="list_active" class="table table-sm table-dark table-striped table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Nivel</th>
                <th>Ocorrências Ativas</th>
                <th>Meios</th>
                <th>Operacionais</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <a href="{{route('goi.create')}}" class="btn btn-secondary">Criar Teatro de Operações</a>
    <hr />
    <h2>Teatros de Operações - Concluidos</h2>
    <table id="list_concluded" class="table table-sm table-dark table-striped table-bordered">
        <thead>
            <tr>
                <th>Inicio</th>
                <th>Fecho</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Nivel</th>
                <th>Ocorrências</th>
                <th>Meios</th>
                <th>Operacionais</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@endsection

@section('javascript')
@parent
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.20/sorting/datetime-moment.js"></script>
<script>
    $.fn.dataTable.moment( 'HH:mm DD/MM/YYYY' );
    $(document).ready(function() {
        let list_active_table = $('#list_active').DataTable({
            "lengthMenu": [[10, 20, -1], [10, 20, "Todos"]],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
            },
            "ajax": {
                "url": "{{route('goi.getActive')}}",
                "dataSrc": ""
            },
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": "<a href='#0'>Abrir</a>"
            }]
        });
        $('#list_active tbody').on( 'click', 'a', function () {
            var data = list_active_table.row($(this).parents('tr')).data();
            location.replace("{{route('goi.single','')}}/"+data[6]);
        });
        let list_concluded_table = $('#list_concluded').DataTable({
            "lengthMenu": [[10, 20, -1], [10, 20, "Todos"]],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
            },
            "ajax": {
                "url": "{{route('goi.getConcluded')}}",
                "dataSrc": ""
            },
            "columnDefs": [{
                "targets": -1,
                "data": null,
                "defaultContent": "<a href='#0'>Abrir</a>"
            }]
        });
        $('#list_concluded tbody').on( 'click', 'a', function () {
            var data = list_concluded_table.row($(this).parents('tr')).data();
            location.replace("{{route('goi.single','')}}/"+data[8]);
        });
    });
</script>
@endsection