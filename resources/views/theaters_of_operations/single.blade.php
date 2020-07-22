@extends('theaters_of_operations/layouts/panel')

@section('pageTitle', 'TO '.$theater_of_operations->name)

@section('style')
@parent
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<link href="https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.css" rel="stylesheet" />
<style>
    #map {
        width: 100%;
        height: 50vh;
    }

    /*
    * DataTables
    */
    .dataTables_wrapper {
        width: 100%;
    }
</style>
@endsection

@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="container">
    <div style="text-align: center">
        <h2>Teatro de Operações - {{$theater_of_operations->name}}</h2>
        <h4>{{$theater_of_operations->type}} - {{$theater_of_operations->level}}</h4>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <p>Nome: {{$theater_of_operations->name}}</p>
            <p>Tipo: {{$theater_of_operations->type}}</p>
            <p>Nivel: {{$theater_of_operations->level}}</p>
            <p>Canal de Criação: {{$theater_of_operations->creation_channel}}</p>
            <p>Nº CDOS: {{$theater_of_operations->cdos}}</p>
            <p>Localização: {{$theater_of_operations->location}} (<a href="#map" onclick="centerMap()">Centrar</a>)</p>
            <p>Ocorrências Ativas: {{$theater_of_operations->getActiveEvents()->count()}}</p>
            <p>Meios: {{$theater_of_operations->getUnits()->count()}}</p>
            <p>Operacionais: {{$theater_of_operations->getCrews()->count()}}</p>
            <p>Oficial de Ligação: @if ($theater_of_operations->coordination->where('role','=','Oficial de Ligação')->first())
                {{$theater_of_operations->coordination->where('role','=','Oficial de Ligação')->first()->name}} -
                {{$theater_of_operations->coordination->where('role','=','Oficial de Ligação')->first()->contact}}@else
                N/A @endif</p>
            <p>Coordenador: @if ($theater_of_operations->coordination->where('role','=','Coordenador')->first())
                {{$theater_of_operations->coordination->where('role','=','Coordenador')->first()->name}} -
                {{$theater_of_operations->coordination->where('role','=','Coordenador')->first()->contact}}@else N/A
                @endif</p>
            @if(!$theater_of_operations->trashed())
            <p>Inicio {{$theater_of_operations->created_at->locale('pt_PT')->diffForHumans()}}</p>
            <a href="{{route('theaters_of_operations.edit',$theater_of_operations->id)}}" class="btn btn-dark">Editar
                TO</a>
            <a href="{{route('theaters_of_operations.close',$theater_of_operations->id)}}" class="btn btn-danger">Fechar
                TO</a>
            @else
            <p>Durou
                {{$theater_of_operations->deleted_at->locale('pt_PT')->diffForHumans($theater_of_operations->created_at)}}
            </p>
            <a href="{{route('theaters_of_operations.reopen',$theater_of_operations->id)}}"
                class="btn btn-danger">Reabrir TO</a>
            @endif

        </div>
        <div class="col-sm-6">
            <div id="map"></div>

        </div>
    </div>
    <div class="row">
        <h4 style="text-align: center;width:100vw;">Fita do Tempo (Resumo) - <a href="{{route('theaters_of_operations.timetape.index', ['type'=>'to','object'=>$theater_of_operations->id])}}">Ver Todo</a></h4>
        <table id="list_timetape" style="width:100%" class="table table-sm table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        @if(!$theater_of_operations->trashed())
        <hr style="text-align: center;width:100vw;" />
        <h6 style="text-align: center;width:100vw;">Adicionar à Fita de Tempo</h6>
        <form style="width:100vw;" method="POST"
            action="{{route('theaters_of_operations.addToTimeTape',['id' => $theater_of_operations->id])}}">
            @csrf
            <div class="row">
                <div class="form-group col-3">
                    <label for="type_selector">Tipo</label>
                    <select class="form-control" id="type_selector" name="type">
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
                    <select class="form-control" id="object_selector" name="object" disabled>
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Descrição" id="timetape_description" name="description" autocomplete="off">
            </div>
            <button type="submit" class="btn btn-secondary">Adicionar</button>
        </form>
        <hr style="text-align: center;width:100vw;" />
        @endif
    </div>
    <div class="row">
        <h4 style="text-align: center; width:100vw">Coordenadores</h4>
        <table id="list_coordination" style="width:100%" class="table table-sm table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cargo</th>
                    <th>Contacto</th>
                    <th>Observações</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        @if(!$theater_of_operations->trashed())
        <a href="{{route('theaters_of_operations.coordination.create',$theater_of_operations->id)}}"
            class="btn btn-secondary">Adicionar Coordenador</a>
        @endif
    </div>
    <div class="row">
        <h4 style="text-align: center; width:100vw">Pontos de Interesse</h4>
        <table id="list_pois" style="width:100%" class="table table-sm table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Localização</th>
                    <th>Observações</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        @if(!$theater_of_operations->trashed())
        <a href="{{route('theaters_of_operations.pois.create',$theater_of_operations->id)}}"
            class="btn btn-secondary">Adicionar Ponto de Interesse</a>
            @endif
    </div>
    <div class="row">
        <h4 style="text-align: center; width:100vw">Ocorrências</h4>
        <table id="list_events" style="width:100%" class="table table-sm table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Localização</th>
                    <th>CODU</th>
                    <th>CDOS</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        @if(!$theater_of_operations->trashed())
        <a href="{{route('theaters_of_operations.events.create',$theater_of_operations->id)}}"
            class="btn btn-secondary">Adicionar Ocorrência</a>
            @endif
    </div>
    <div class="row">
        <h4 style="text-align: center; width:100vw">Meios</h4>
        <table id="list_units" style="width:100%" class="table table-sm table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Nº de Cauda</th>
                    <th>Matricula</th>
                    <th>Estrutura</th>
                    <th>Status</th>
                    <th>Destacamento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        @if(!$theater_of_operations->trashed())
        <a href="{{route('theaters_of_operations.units.create',$theater_of_operations->id)}}"
            class="btn btn-secondary">Adicionar Meio</a>
        <a href="{{route('theaters_of_operations.units.recreate',$theater_of_operations->id)}}"
            class="btn btn-secondary">Readicionar Meio</a>   
            @endif
    </div>
    <div class="row">
        <h4 style="text-align: center; width:100vw">Operacionais</h4>
        <table id="list_crews" style="width:100%" class="table table-sm table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Contacto</th>
                    <th>Idade</th>
                    <th>Formação</th>
                    <th>Observações</th>
                    <th>Destacamento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        @if(!$theater_of_operations->trashed())
        <a href="{{route('theaters_of_operations.crews.create',$theater_of_operations->id)}}"
            class="btn btn-secondary">Adicionar Operacional</a>
        <a href="{{route('theaters_of_operations.crews.recreate',$theater_of_operations->id)}}"
             class="btn btn-secondary">Readicionar Operacional</a>   
            @endif
    </div>
    <div class="row">
        <h4 style="text-align: center; width:100vw">Comunicações</h4>
        <table id="list_communication_channels" style="width:100%"
            class="table table-sm table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Canal</th>
                    <th>Observações</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        @if(!$theater_of_operations->trashed())
        <a href="{{route('theaters_of_operations.communication_channels.create',$theater_of_operations->id)}}"
            class="btn btn-secondary">Adicionar Canal de Comunicações</a>
            @endif
    </div>
    <div class="row">
        <h4 style="text-align: center; width:100vw">Observações</h4>
        <div class="form-group" style="width:100vw">
            @if(!$theater_of_operations->trashed())
            <textarea class="form-control" placeholder="Observações" id="observations"
                rows="3">{{$theater_of_operations->observations}}</textarea>
            @else
            <p>{{$theater_of_operations->observations}}</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('javascript')
@parent
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.20/sorting/datetime-moment.js"></script>
<script src="{{ mix('js/map.js') }}"></script>
<script>
    $('#type_selector').select2({
        theme: 'bootstrap4',
    });
    $("#type_selector").change(function() {
        let value = $(this).val();
        if(value) {
            loadObjects(value);
        }
    });
    function loadObjects(_type) {
        type = _type;
        $("#timetape_description").prop('disabled', true);
        $("#object_selector").prop('disabled', true);
        $("#object_selector").html("<option></option>");
        switch (type) {
            case "to":
                $("#timetape_description").prop('disabled', false);
                return;
            case "poi":
                $.get( "{{route('theaters_of_operations.objects.poi',$theater_of_operations->id)}}", function( data ) {
                    data.forEach(element => {
                        $("#object_selector").append("<option value='"+element.id+"'>"+element.name+" </option>");
                    });
                }, "json" );
                break;
            case "event":
                $.get( "{{route('theaters_of_operations.objects.event',$theater_of_operations->id)}}", function( data ) {
                    data.forEach(element => {
                        $("#object_selector").append("<option value='"+element.id+"'>"+element.location+"</option>");
                    });
                }, "json" );
                break;
            case "unit":
                $.get( "{{route('theaters_of_operations.objects.unit',$theater_of_operations->id)}}", function( data ) {
                    data.forEach(element => {
                        $("#object_selector").append("<option value='"+element.id+"'>"+element.tail_number+" "+element.plate+"</option>");
                    });
                }, "json" );
                break;
            case "crew":
                $.get( "{{route('theaters_of_operations.objects.crew',$theater_of_operations->id)}}", function( data ) {
                    data.forEach(element => {
                        $("#object_selector").append("<option value='"+element.id+"'>"+element.name+"</option>");
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
                $("#timetape_description").prop('disabled', false);
            }
            else {
                $("#timetape_description").prop('disabled', true);
            }
        });
    }

    let map = new Map({{$theater_of_operations->id}}, 'map', 11, {{$theater_of_operations->lat}}, {{$theater_of_operations->long}});
    $.fn.dataTable.moment( 'HH:mm DD/MM/YYYY' );
    let list_timetape_table = $('#list_timetape').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.getBriefTimeTape',$theater_of_operations->id)}}",
            "dataSrc": ""
        },
        "order": [[ 0, "desc" ]],
        "paging":   false,
        "info":     false,
        "searching": false
    });

    let list_coordination_table = $('#list_coordination').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.getCoordination',$theater_of_operations->id)}}",
            "dataSrc": ""
        },
        "order": [[ 0, "desc" ]],
        "sort":     false,
        "paging":   false,
        "info":     false,
        "searching": false,
        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": "<a href='#0'>Editar</a>"
        }]
    });
    $('#list_coordination tbody').on( 'click', 'a', function () {
        var data = list_coordination_table.row($(this).parents('tr')).data();
        let url = "{{route('theaters_of_operations.coordination.edit',['id' => $theater_of_operations->id, 'coordination_id' => '-1'])}}/";
            url = url.replace("-1",data[4]);
        location.replace(url);
    });

    let list_pois_table = $('#list_pois').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.getPOIs',$theater_of_operations->id)}}",
            "dataSrc": ""
        },
        "order": [[ 0, "desc" ]],
        "sort":     false,
        "paging":   false,
        "info":     false,
        "searching": false,
        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": "<a href='#map' data-action='zoom-in'>Zoom In</a> | @if(!$theater_of_operations->trashed())<a href='#0' data-action='edit'>Editar</a>@endif"
        }]
    });
    $('#list_pois tbody').on( 'click', 'a', function () {
        var data = list_pois_table.row($(this).parents('tr')).data();
        let action = $(this).data('action');
        if(action == "zoom-in") {
            map.flyTo({
                center: [data[5], data[4]],
                essential: true
            });
        }
        else if(action == "edit"){
            let url = "{{route('theaters_of_operations.pois.edit',['id' => $theater_of_operations->id, 'poi_id' => '-1'])}}/";
            url = url.replace("-1",data[6]);
            location.replace(url);
        }
    });

    let list_events_table = $('#list_events').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.getEvents',$theater_of_operations->id)}}",
            "dataSrc": ""
        },
        "order": [[ 0, "desc" ]],
        "sort":     false,
        "paging":   false,
        "info":     false,
        "searching": false,
        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": "<a href='#map' data-action='zoom-in'>Zoom In</a> | <a href='#0' data-action='open'>Abrir</a>"
        }]
    });
    $('#list_events tbody').on( 'click', 'a', function () {
        var data = list_events_table.row($(this).parents('tr')).data();
        let action = $(this).data('action');
        if(action == "zoom-in") {
            map.zoomOn(data[5],data[6],15);
        }
        else if(action == "open"){
            let url = "{{route('theaters_of_operations.events.single',['id'=>$theater_of_operations->id,'event_id' => '-1'])}}";
            url = url.split("-1").join(data[7]);
            location.replace(url);
        }
    });

    let list_units_table = $('#list_units').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.getUnits',$theater_of_operations->id)}}",
            "dataSrc": ""
        },
        "order": [[ 0, "desc" ]],
        "sort":     false,
        "paging":   false,
        "info":     false,
        "searching": false,
        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": "<a href='#map' data-action='zoom-in'>Zoom In</a> | <a href='#0' data-action='open'>Abrir</a>"
        }]
    });
    $('#list_units tbody').on( 'click', 'a', function () {
        var data = list_units_table.row($(this).parents('tr')).data();
        let action = $(this).data('action');
        if(action == "zoom-in") {
            map.zoomOn(data[6],data[7],15);
        }
        else if(action == "open"){
            let url = "{{route('theaters_of_operations.units.single',['id'=>$theater_of_operations->id,'unit_id' => '-1'])}}";
            url = url.split("-1").join(data[8]);
            location.replace(url);
        }
    });

    let list_crews_table = $('#list_crews').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.getCrews',$theater_of_operations->id)}}",
            "dataSrc": ""
        },
        "order": [[ 0, "desc" ]],
        "sort":     false,
        "paging":   false,
        "info":     false,
        "searching": false,
        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": "<a href='#map' data-action='zoom-in'>Zoom In</a> | <a href='#0' data-action='open'>Abrir</a>"
        }]
    });
    $('#list_crews tbody').on( 'click', 'a', function () {
        var data = list_crews_table.row($(this).parents('tr')).data();
        let action = $(this).data('action');
        if(action == "zoom-in") {
            map.zoomOn(data[6],data[7],15);
        }
        else if(action == "open"){
            let url = "{{route('theaters_of_operations.crews.single',['id'=>$theater_of_operations->id,'crew_id' => '-1'])}}";
            url = url.split("-1").join(data[10]);
            location.replace(url);
        }
    });

    let list_communication_channels_table = $('#list_communication_channels').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.getCommunicationChannels',$theater_of_operations->id)}}",
            "dataSrc": ""
        },
        "order": [[ 0, "desc" ]],
        "sort":     false,
        "paging":   false,
        "info":     false,
        "searching": false,
        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": "@if(!$theater_of_operations->trashed())<a href='#0' data-action='edit'>Editar</a>@endif"
        }]
    });


    $('#list_communication_channels tbody').on( 'click', 'a', function () {
        var data = list_communication_channels_table.row($(this).parents('tr')).data();
        let action = $(this).data('action');
        let url = "{{route('theaters_of_operations.communication_channels.edit',['id' => $theater_of_operations->id, 'communication_channel_id' => '-1'])}}";
        url = url.split("-1").join(data[3]);
        location.replace(url);
    });


    function centerMap() {
        map.recenter();
    }

</script>
@if(!$theater_of_operations->trashed())
<script>
    var typingTimer;
    var doneTypingInterval = 250;
    var $input = $('#observations');

    $input.on('blur', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    $input.on('keyup', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    $input.on('paste', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    $input.on('focus', function () {
        clearTimeout(typingTimer);
    });

    $input.on('keydown', function () {
        clearTimeout(typingTimer);
    });

    function doneTyping () {
        let observations = $input.val();
        axios.post("{{route('theaters_of_operations.updateObservations',$theater_of_operations->id)}}", {
            observations: observations
        })
        .then(function (response) {
        });
    }
</script>
@endif
@endsection