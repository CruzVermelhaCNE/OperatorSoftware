@extends('goi/layouts/panel')

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
                <option></option>
                <option value="to">Teatro de Operações</option>
                <option value="poi">Ponto de Interesse</option>
                <option value="event">Ocorrências</option>
                <option value="unit">Meio</option>
                <option value="crew">Operacional</option>
            </select>
        </div>
        <div class="form-group col-3">
            <label for="object_selector">Objecto</label>
            <select class="form-control" id="object_selector" disabled>
                <option></option>
            </select>
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
    </table>
</div>
@endsection

@section('javascript')
@parent

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script>
<script>
    var type = null;
    var table = null;
    var load_object = null;
    var table_created = false;
    $(document).ready(function() {
        $.fn.dataTable.moment( 'YYYY-MM-DD HH:mm:ss' );
        $('#type_selector').select2({
            theme: 'bootstrap4',
        });
        const urlParams = new URLSearchParams(window.location.search);
        let load_type = urlParams.get('type');
        load_object = urlParams.get('object');
        if(load_object == null) {
            loadTable("all");
        }
        else {
            loadObjects(load_type);
        }
    });
    $("#type_selector").change(function() {
        let value = $(this).val();
        if(value) {
            loadObjects(value);
        }
    });

    function appendLeadingZeroes(n){
        if(n <= 9){
            return "0" + n;
        }
        return n
    }

    function loadObjects(_type) {
        type = _type;
        $("#object_selector").prop('disabled', true);
        $("#object_selector").html("<option></option>");
        switch (type) {
            case "to":
                $.get( "{{ route('goi.timetape.objects.to') }}", function( data ) {
                    data.forEach(element => {
                        let date = new Date(element.created_at);
                        $("#object_selector").append("<option value='"+element.id+"'>"+element.name+" - "+appendLeadingZeroes(date.getDate()) + "-" + appendLeadingZeroes((date.getMonth() + 1)) + "-" + date.getFullYear()+"</option>");
                    });
                }, "json" );
                break;
            case "poi":
                $.get( "{{ route('goi.timetape.objects.poi') }}", function( data ) {
                    data.forEach(element => {
                        let to_date = new Date(element.theater_of_operations.created_at);
                        $("#object_selector").append("<option value='"+element.id+"'>"+element.name+" - "+element.theater_of_operations.name+" - "+appendLeadingZeroes(to_date.getDate()) + "-" + appendLeadingZeroes((to_date.getMonth() + 1)) + "-" + to_date.getFullYear()+"</option>");
                    });
                }, "json" );
                break;
            case "event":
                $.get( "{{ route('goi.timetape.objects.event') }}", function( data ) {
                    data.forEach(element => {
                        let date = new Date(element.created_at);
                        $("#object_selector").append("<option value='"+element.id+"'>"+element.location+" - "+element.theater_of_operations.name+" - "+appendLeadingZeroes(date.getDate()) + "-" + appendLeadingZeroes((date.getMonth() + 1)) + "-" + date.getFullYear()+"</option>");
                    });
                }, "json" );
                break;
            case "unit":
                $.get( "{{ route('goi.timetape.objects.unit') }}", function( data ) {
                    data.forEach(element => {
                        let to_date = new Date(element.theater_of_operations.created_at);
                        $("#object_selector").append("<option value='"+element.id+"'>"+element.tail_number+" "+element.plate+" - "+element.theater_of_operations.name+" - "+appendLeadingZeroes(to_date.getDate()) + "-" + appendLeadingZeroes((to_date.getMonth() + 1)) + "-" + to_date.getFullYear()+"</option>");
                    });
                }, "json" );
                break;
            case "crew":
                $.get( "{{ route('goi.timetape.objects.crew') }}", function( data ) {
                    data.forEach(element => {
                        let to_date = new Date(element.theater_of_operations.created_at);
                        $("#object_selector").append("<option value='"+element.id+"'>"+element.name+" - "+element.theater_of_operations.name+" - "+appendLeadingZeroes(to_date.getDate()) + "-" + appendLeadingZeroes((to_date.getMonth() + 1)) + "-" + to_date.getFullYear()+"</option>");
                    });
                }, "json" );
            default:
                break;
        }
        $("#object_selector").prop('disabled', false);
        $('#object_selector').select2({
            theme: 'bootstrap4',
        });
        $("#object_selector").change(function() {
            let value = $(this).val();
            if(value) {
                loadTable(value);
            }
        });
        if(load_object !== null) {
            loadTable(load_object);
            load_object = null;
        }
    }

    function loadTable(id) {
        if(table_created) {
            $('#timetape').DataTable().destroy();
        }
        else {
            table_created = true;
        }
        $('#timetape tbody').empty();
        if(id != "all") {
            table = $("#timetape").dataTable( {
                "ajax": {
                    "url": "{{ route('goi.timetape.index') }}/"+type+"/"+id,
                    "dataSrc": ""
                },
                "columns": [
                    { "data": "date" },
                    { "data": "description" },
                ],
                "order": [[ 0, "desc" ]]
            });
        }
        else {
            table = $("#timetape").dataTable( {
                "ajax": {
                    "url": "{{ route('goi.timetape.all') }}",
                    "dataSrc": ""
                },
                "columns": [
                    { "data": "date" },
                    { "data": "description" },
                ],
                "order": [[ 0, "desc" ]]
            });
        }
    }  
    </script>
@endsection