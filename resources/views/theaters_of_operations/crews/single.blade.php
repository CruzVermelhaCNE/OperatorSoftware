@extends('theaters_of_operations/layouts/panel')

@section('pageTitle', 'Operacional '.$crew->name)

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
<div class="container">
    <div style="text-align: center">
        <h2>Operacional - {{$crew->name}}</h2>
        <h4>{{$crew->course}} - {{$crew->contact}}</h4>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="row">
        <div class="col-sm-6">
            <p>Nome: {{$crew->name}}</p>
            <p>Contacto: {{$crew->contact}}</p>
            <p>Idade: {{$crew->age}}</p>
            <p>Formação: {{$crew->course}}</p>
            <p>Localização: {{$crew->deployment}} (<a href="#map" onclick="centerMap()">Centrar</a>)</p>
            @if(!$crew->trashed())
            <p>No TO {{$crew->created_at->locale('pt_PT')->diffForHumans()}}</p>
            <a href="{{route('theaters_of_operations.crews.demobilize',["id" => $crew->theater_of_operations->id, "crew_id" => $crew->id])}}"
                class="btn btn-danger">Desmobilizar Operacional</a>
            @else
            <p>Esteve no TO {{$crew->deleted_at->locale('pt_PT')->diffForHumans($crew->created_at)}}</p>
            @endif
            <a href="{{route('theaters_of_operations.single',["id" => $crew->theater_of_operations->id])}}"
                class="btn btn-info">Abrir TO</a>
            @if ($crew->unit)
            <a href="{{route('theaters_of_operations.units.single',["id" => $crew->theater_of_operations->id, "unit_id" => $crew->unit->id])}}"
                class="btn btn-info">Abrir Meio</a>
            @endif
            @if(!$crew->theater_of_operations->trashed())
            <a href="{{route('theaters_of_operations.crews.edit',["id" => $crew->theater_of_operations->id, "crew_id" => $crew->id])}}"
                class="btn btn-dark">Editar</a>
            @if(!$crew->trashed())
            <h2>Destacar</h2>
            <h5>Meios</h5>
            <form
                action="{{route('theaters_of_operations.crews.assignToUnit',["id" => $crew->theater_of_operations->id, "crew_id" => $crew->id])}}"
                method="POST">
                @csrf
                <div class="input-group">
                    <select class="custom-select" id="list_units" name="unit_id">
                        <option @if ($crew->theater_of_operations_unit_id == null) selected @endif>Escolher</option>
                        @foreach ($crew->theater_of_operations->getUnits() as $unit)
                        <option value="{{$unit->id}}" @if ($crew->theater_of_operations_unit_id == $unit->id) selected
                            @endif>{{$unit->tail_number?$unit->tail_number:$unit->plate}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-warning" type="submit">Destacar</button>
                    </div>
                </div>
            </form>
            <h5>Pontos de Interesse</h5>
            <form
                action="{{route('theaters_of_operations.crews.assignToPOI',["id" => $crew->theater_of_operations->id, "crew_id" => $crew->id])}}"
                method="POST">
                @csrf
                <div class="input-group">
                    <select class="custom-select" id="list_pois" name="poi_id">
                        <option @if ($crew->theater_of_operations_poi_id == null) selected @endif>Escolher</option>
                        @foreach ($crew->theater_of_operations->pois as $poi)
                        <option value="{{$poi->id}}" @if ($crew->theater_of_operations_poi_id == $poi->id) selected
                            @endif>{{$poi->name}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-warning" type="submit">Destacar</button>
                    </div>
                </div>
            </form>
            @endif
            @endif
        </div>
        <div class="col-sm-6">
            <div id="map"></div>

        </div>
    </div>
    <div class="row">
        <h4 style="text-align: center;width:100vw;">Fita do Tempo (Resumo) - <a href="{{route('theaters_of_operations.timetape.index', ['type'=>'crew','object'=>$crew->id])}}">Ver Todo</a></h4>
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
    </div>
    <div class="row">
        <h4 style="text-align: center; width:100vw">Observações</h4>
        <div class="form-group" style="width:100vw">
            @if(!$crew->trashed())
            <textarea class="form-control" placeholder="Observações" id="observations"
                rows="3">{{$crew->observations}}</textarea>
            @else
            <p>{{$crew->observations}}</p>
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
    let map = new Map({{$crew->theater_of_operations->id}}, 'map', 11, {{$crew->lat}}, {{$crew->long}});
    $.fn.dataTable.moment( 'HH:mm DD/MM/YYYY' );
    let list_timetape_table = $('#list_timetape').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.crews.getBriefTimeTape',['id'=>$crew->theater_of_operations->id,'crew_id'=>$crew->id])}}",
            "dataSrc": ""
        },
        "order": [[ 0, "desc" ]],
        "paging":   false,
        "info":     false,
        "searching": false
    });


    function centerMap() {
        map.recenter();
    }

</script>
@if(!$crew->trashed())
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
        axios.post("{{route('theaters_of_operations.crews.updateObservations',['id' => $crew->theater_of_operations->id, 'crew_id' => $crew->id])}}", {
            observations: observations
        })
        .then(function (response) {
        });
    }
</script>
@endif
@endsection