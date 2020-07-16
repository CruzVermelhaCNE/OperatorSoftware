@extends('theaters_of_operations/layouts/panel')

@section('pageTitle', 'Fita de Tempo')

@section('style')
@parent
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
<div class="container">
    <h2>Fita de Tempo</h2>
    <h4>Filtros</h4>
    <div class="row">
        <div class="form-group col-3">
            <label for="type_selector">Tipo</label>
            <select class="form-control" id="type_selector">
                <option>Teatro de Operações</option>
                <option>Ponto de Interesse</option>
                <option>Evento</option>
                <option>Meio</option>
                <option>Operacional</option>
            </select>
        </div>
        <div class="form-group col-3">
            <label for="object_selector">Objecto</label>
            <input type="number" min="0" step="1" class="form-control" id="object_selector" disabled>
        </div>
        <div class="form-group col-3">
            <a href="#" class="btn btn-secondary" style="margin-top: 1.8rem;">Aplicar</a>
        </div>
    </div>
    <hr />
    <table id="timetape" class="table table-sm table-dark table-striped table-bordered">
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection

@section('javascript')
@parent
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/pagination/scrolling.js"></script>
<script>
    $(document).ready(function() {
        $('#type_selector').select2({
            theme: 'bootstrap4',
        });
        $("#timetape").dataTable( {
            "sPaginationType": "scrolling",
            "ajax": {
                "url": "{{ route('theaters_of_operations.timetape.all') }}",
                "dataSrc": function (object) {
                    console.log(object);
                    return object;
                }
            },
            "columns": [
                { "data": "created_at" },
                { "data": "description" },
            ]
        });
    });
</script>
@endsection