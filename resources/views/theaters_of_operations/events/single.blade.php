@extends('theaters_of_operations/layouts/panel')

@section('pageTitle', 'Ocorrência '.$event->type.' - '.$event->location)

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

    textarea {
        resize: vertical;
        min-height: calc(4.5em + .75rem);
    }
</style>
@endsection

@section('content')
<div class="container">
    <div style="text-align: center">
        <h2>Ocorrência {{$event->type}} - {{$event->location}}</h2>
        @if ($event->codu)<h4>CODU - {{$event->codu}}</h4>@endif
        @if ($event->cdos)<h4>CDOS - {{$event->cdos}}</h4>@endif
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
            <p>Tipo: {{$event->type}}</p>
            <p>Status: {{$event->status_text}}</p>
            <p>Localização: {{$event->location}} (<a href="#map" onclick="centerMap()">Centrar</a>)</p>
            <a href="{{route('theaters_of_operations.single',["id" => $event->theater_of_operations->id])}}"
                class="btn btn-info">Abrir TO</a>
            @if(!$event->isFinished())
            <a href="{{route('theaters_of_operations.events.edit',["id" => $event->theater_of_operations->id, "event_id" => $event->id])}}"
                class="btn btn-dark">Editar</a>
            @endif
            <h2>Status</h2>
            <form
                action="{{route('theaters_of_operations.events.updateStatus',["id" => $event->theater_of_operations->id, "event_id" => $event->id])}}"
                method="POST">
                @csrf
                <div class="input-group">
                    <select class="custom-select" name="status">
                        <option value="0" @if ($event->status == 0) selected @endif>Anulada</option>
                        <option value="1" @if ($event->status == 1) selected @endif>Despacho</option>
                        <option value="2" @if ($event->status == 2) selected @endif>A Decorrer</option>
                        <option value="3" @if ($event->status == 3) selected @endif>Em Conclusão</option>
                        <option value="4" @if ($event->status == 4) selected @endif>Terminada</option>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Atualizar</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-6">
            <div id="map"></div>
            <br />
        </div>
    </div>
    <div class="row">
        <h4 style="text-align: center;width:100vw;">Fita do Tempo (Resumo) - <a href="{{route('theaters_of_operations.timetape.index', ['type'=>'event','object'=>$event->id])}}">Ver Todo</a></h4>
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
        <h4 style="text-align: center;width:100vw;margin-top:2vh">Vitimas</a></h4>
        <table id="list_victims" style="width:100%" class="table table-sm table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Nome</th>
                    <th>Idade</th>
                    <th>Destino</th>
                    <th>Acções</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        @if(!$event->isFinished())
        <a href="{{route('theaters_of_operations.events.victims.create',["id" => $event->theater_of_operations->id, "event_id" => $event->id])}}"
            class="btn btn-secondary">Adicionar Vitima</a>
        @endif
    </div>
    <div class="row">
        <h4 style="text-align: center;width:100vw;">Meios</a></h4>
        <table id="list_units" style="width:100%" class="table table-sm table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Nº de Cauda</th>
                    <th>Matricula</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        @if(!$event->isFinished())
        <hr style="text-align: center;width:100vw;" />
        <h6 style="text-align: center;width:100vw;">Accionar Meio</h6>
        <form style="width:100vw;" method="POST"
            action="{{route('theaters_of_operations.events.deployUnit',['id' => $event->theater_of_operations->id, 'event_id' => $event->id])}}">
            @csrf
            <div class="form-group">
                <select class="form-control" name="unit">
                    @foreach ($available_units as $unit)
                    <option value="{{$unit->id}}">{{$unit->tail_number?$unit->tail_number:$unit->plate}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">Accionar</button>
        </form>
        <hr style="text-align: center;width:100vw;" />
        @endif
    </div>
    <div class="row">
        <h4 style="text-align: center; width:100vw">Observações</h4>
        <div class="form-group" style="width:100vw">
            @if(!$event->isFinished())
            <textarea class="form-control" placeholder="Observações" id="observations"
                rows="3">{{$event->observations}}</textarea>
            @else
            <p>{{$event->observations}}</p>
            @endif
        </div>
    </div>
</div>
<div id="victim" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark">
        </div>
    </div>
</div>
<div class="modal fade" id="victim_data" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark">
        </div>
    </div>
</div>
<div class="modal fade" id="victim_location" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark">
        </div>
    </div>
</div>
<div class="modal fade" id="victim_timings" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark">
        </div>
    </div>
</div>
<div class="modal fade" id="unit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark">
        </div>
    </div>
</div>
<div class="modal fade" id="unit_timings" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark">
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
<script type="text/template" id="template_victim">
    <div class="modal-header">
        <h5 class="modal-title">Vitima #[[ID]]</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-6">
                <h4>Dados</h4>
                <p>Nome: [[NAME]]</p>
                <p>Idade: [[AGE]]</p>
                <p>Sexo: [[GENDER]]</p>
                <p>SNS: [[SNS]]</p>
                @if (!$event->isFinished())
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal"
                    data-target="#victim_data">Editar Dados</button>
                <a id="victim_delete" style="display:none" href="{{route('theaters_of_operations.events.victims.delete',['id'=>$event->theater_of_operations->id,'event_id'=>$event->id,'victim_id'=>'-1'])}}" class="btn btn-danger">Apagar Vitima</a>
                @endif
            </div>
            <div class="col-6">
                <h4>Meio Atribuida</h4>
                <p>Nome: [[EVENT_UNIT_NAME]]</p>
                <a href="#0" class="btn btn-primary" onclick="openUnit([[EVENT_UNIT_ID]])" id="event_unit_open" style="display:none;margin-bottom:1vh;">Abrir Meio</a>
                @if (!$event->isFinished())
                <form method="POST" action="{{route('theaters_of_operations.events.victims.assignUnit',['id'=>$event->theater_of_operations->id,'event_id'=>$event->id,'victim_id'=>'-1'])}}">
                    @csrf
                    <div class="input-group">
                        <select class="custom-select" id="victim_assign_unit" name="event_unit">
                            @foreach ($event->event_units as $event_unit)
                            <option value="{{$event_unit->id}}">{{$event_unit->unit->tail_number?$event_unit->unit->tail_number:$event_unit->unit->plate}}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Atualizar</button>
                        </div>
                    </div>
                </form>
                @endif
                <h4>Destino</h4>
                <p>Localização: [[DESTINATION]]</p>
                <div class="row">
                    <div class="col-6">
                        <p>Latitude: [[LAT]]</p>
                    </div>
                    <div class="col-6">
                        <p>Longitude: [[LONG]]</p>
                    </div>
                </div>
                @if (!$event->isFinished())
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal"
                    data-target="#victim_location">Editar Destino</button>
                @endif
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-6">
                <h4>Status</h4>
                <p>Estado Atual: [[STATUSTEXT]]</p>
                <form method="POST" action="{{route('theaters_of_operations.events.victims.updateStatus',['id'=>$event->theater_of_operations->id,'event_id'=>$event->id,'victim_id'=>'-1'])}}">
                    @csrf
                    <div class="input-group">
                        <select class="custom-select" id="victim_status" name="status">
                            <option value="0">Anulada</option>
                            <option value="1">No Local</option>
                            <option value="2">Assistida no Local</option>
                            <option value="3">Abandonou o Local</option>
                            <option value="4">Recusou Assistência</option>
                            <option value="5">A Caminho do Destino</option>
                            <option value="6">No Destino</option>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Atualizar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-6">
                <h4>Horas</h4>
                <div class="victim_timings_canceled" style="display:none">
                    <p>Anulado às: [[CANCELED_AT]]</p>
                </div>
                <div class="victim_timings_normal" style="display:none">
                    <p>Saída do Local: [[DEPARTURE_FROM_SCENE]]</p>
                    <p>Chegada ao Destino: [[ARRIVAL_ON_DESTINATION]]</p>
                </div>
                <div class="victim_timings_assisted_on_scene" style="display:none">
                    <p>Assistido no Local: [[ASSISTED_ON_SCENE]]</p>
                </div>
                <div class="victim_timings_abandoned_scene" style="display:none">
                    <p>Abandonou o Local: [[ABANDONED_SCENE]]</p>
                </div>
                <div class="victim_timings_refused_assistance" style="display:none">
                    <p>Recusou Assistência: [[REFUSED_ASSISTANCE]]</p>
                </div>
                @if (!$event->isFinished())
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal"
                    data-target="#victim_timings">Editar Horas</button>
                @endif
            </div>
        </div>
        <hr />
        <h4>Observações</h4>
        @if(!$event->isFinished())
        <textarea class="form-control" placeholder="Observações" id="victim_observations"
            rows="3" data-id="[[ID]]">[[OBSERVATIONS]]</textarea>
        @else
        <p>[[OBSERVATIONS]]</p>
        @endif
    </div>
</script>
<script type="text/template" id="template_victim_data">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Editar Dados</h5>
        <button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#victim"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form metohd="POST" action="{{route('theaters_of_operations.events.victims.updateData',['id'=>$event->theater_of_operations->id,'event_id'=>$event->id,'victim_id'=>'-1'])}}">
        <div class="modal-body">
            @csrf
            <p>Nome: <input type="text" class="form-control" name="name" value="[[NAME]]" /></p>
            <p>Idade: <input type="number" class="form-control" name="age" value="[[AGE]]" /></p>
            <p>Sexo: <select class="custom-select" id="victim_data_gender" name="gender">
                <option value="0">Masculino</option>
                <option value="1">Feminino</option>
            </select></p>
            <p>SNS: <input type="number" class="form-control" name="sns" value="[[SNS]]" /></p>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</script>
<script type="text/template" id="template_victim_location">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Editar Destino</h5>
        <button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#victim"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form metohd="POST" action="{{route('theaters_of_operations.events.victims.updateDestination',['id'=>$event->theater_of_operations->id,'event_id'=>$event->id,'victim_id'=>'-1'])}}">
        <div class="modal-body">
                @csrf
                <p>Destino:</p>
                <input type="text" class="form-control" value="[[DESTINATION]]" name="destination" />
                <p>Latitude:</p>
                <input type="number" class="form-control" value="[[LAT]]" step="0.000001" name="destination_lat" />
                <p>Longitude:</p>
                <input type="number" class="form-control" value="[[LONG]]" step="0.000001" name="destination_long" />
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</script>
<script type="text/template" id="template_victim_timings">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Editar Horas</h5>
        <button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#victim"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form method="POST" action="{{route('theaters_of_operations.events.victims.updateTimings',['id'=>$event->theater_of_operations->id,'event_id'=>$event->id,'victim_id'=>'-1'])}}">
        <div class="modal-body">
            @csrf
            <div class="victim_timings_canceled" style="display:none">
                <p>Cancelado às:</p>
                <input type="datetime-local" class="form-control" id="template_victim_timings_canceled_at" name="canceled_at" value="[[CANCELED_AT]]" />
            </div>
            <div class="victim_timings_normal" style="display:none">
                <p>Saída do Local:</p>
                <input type="datetime-local" class="form-control" id="template_victim_timings_departure_from_scene" name="departure_from_scene" value="[[DEPARTURE_FROM_SCENE]]" />
                <p>Chegada ao Destino:</p>
                <input type="datetime-local" class="form-control" id="template_victim_timings_arrival_on_destination" name="arrival_on_destination" value="[[ARRIVAL_ON_DESTINATION]]" />
            </div>
            <div class="victim_timings_assisted_on_scene" style="display:none">
                <p>Assistido no Local:</p>
                <input type="datetime-local" class="form-control" id="template_victim_timings_assisted_on_scene" name="assisted_on_scene" value="[[ASSISTED_ON_SCENE]]" />
            </div>
            <div class="victim_timings_abandoned_scene" style="display:none">
                <p>Abandonou o Local:</p>
                <input type="datetime-local" class="form-control" id="template_victim_timings_abandoned_scene" name="abandoned_scene" value="[[ABANDONED_SCENE]]" />
            </div>
            <div class="victim_timings_refused_assistance" style="display:none">
                <p>Recusou Assistência:</p>
                <input type="datetime-local" class="form-control" id="template_victim_timings_refused_assistance" name="refused_assistance" value="[[REFUSED_ASSISTANCE]]" />
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</script>
<script type="text/template" id="template_unit">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Meio [[NAME]]</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-6">
                <h4>Dados</h4>
                <p>Tipo: [[TYPE]]</p>
                <p>Nº de Cauda: [[TAIL_NUMBER]]</p>
                <p>Matricula: [[PLATE]]</p>
                <p>Estrutura: [[STRUCTURE]]</p>
                <h4>Status</h4>
                <p>Estado Atual: [[STATUSTEXT]]</p>
                <form method="POST" action="{{route('theaters_of_operations.units.updateStatus',['id'=>$event->theater_of_operations->id,'unit_id'=>'-2'])}}">
                    @csrf
                    <div class="input-group">
                        <select class="custom-select" id="unit_status" name="status">
                            <option value="2">Despacho</option>
                            <option value="3">A Caminho do Local</option>
                            <option value="4">No Local</option>
                            <option value="5">A Caminho do Destino</option>
                            <option value="6">No Destino</option>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Atualizar</button>
                        </div>
                    </div>
                </form>
                <a href="{{route('theaters_of_operations.units.single',['id'=>$event->theater_of_operations->id,'unit_id' => '-2'])}}" class="btn btn-primary">Abrir Meio</a>
            </div>
            <div class="col-6">
                <h4>Horas</h4>
                <p>Activação: [[ACTIVATION]]</p>
                <p>A Caminho do Local: [[ON_WAY_TO_SCENE]]</p>
                <p>Chegada ao Local: [[ARRIVAL_ON_SCENE]]</p>            
                <p>Saida do Local: [[DEPARTURE_FROM_SCENE]]</p>
                <p>Chegada ao Destino: [[ARRIVAL_ON_DESTINATION]]</p>
                <p>Saida do Destino: [[DEPARTURE_FROM_DESTINATION]]</p>
                <p>Disponivel: [[AVAILABLE]]</p>
                @if (!$event->isFinished())
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal"
                    data-target="#unit_timings">Editar Horas</button>
                @endif
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-6">
                <h4>Tripulação</h4>
                <table id="list_unit_crews" style="width:100%" class="table table-sm table-dark table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Contacto</th>
                            <th>Idade</th>
                            <th>Formação</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <h4>Vitimas</h4>
                <table id="list_unit_victims" style="width:100%" class="table table-sm table-dark table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Idade</th>
                            <th>Destino</th>
                            <th>Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            
        </div>
        <hr />
    </div>
</script>
<script type="text/template" id="template_unit_timings">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Editar Horas</h5>
        <button type="button" class="close" data-dismiss="modal" data-toggle="modal" data-target="#unit"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form metohd="POST" action="{{route('theaters_of_operations.events.units.updateTimings',['id'=>$event->theater_of_operations->id,'event_id'=>$event->id,'event_unit_id'=>'-1'])}}">
        <div class="modal-body">
            @csrf
            <p>Activação:</p>
            <input type="datetime-local" class="form-control" id="template_unit_timings_activation" name="activation" value="[[ACTIVATION]]" />
            <p>A Caminho do Local:</p>
            <input type="datetime-local" class="form-control" id="template_unit_timings_on_way_to_scene" name="on_way_to_scene" value="[[ON_WAY_TO_SCENE]]" />
            <p>Chegada ao Local:</p>
            <input type="datetime-local" class="form-control" id="template_unit_timings_arrival_on_scene" name="arrival_on_scene" value="[[ARRIVAL_ON_SCENE]]" />
            <p>Saida do Local:</p>
            <input type="datetime-local" class="form-control" id="template_unit_timings_departure_from_scene" name="departure_from_scene" value="[[DEPARTURE_FROM_SCENE]]" />
            <p>Chegada ao Destino:</p>
            <input type="datetime-local" class="form-control" id="template_unit_timings_arrival_on_destination" name="arrival_on_destination" value="[[ARRIVAL_ON_DESTINATION]]" />
            <p>Saida do Destino:</p>
            <input type="datetime-local" class="form-control" id="template_unit_timings_departure_from_destination" name="departure_from_destination" value="[[DEPARTURE_FROM_DESTINATION]]" />
            <p>Disponivel:</p>
            <input type="datetime-local" class="form-control" id="template_unit_timings_available" name="available" value="[[AVAILABLE]]" />
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</script>
<script>
    let map = new Map({{$event->theater_of_operations->id}}, 'map', 15, {{$event->lat}}, {{$event->long}});

    function centerMap() {
        map.recenter();
    }
</script>
<script>
    $.fn.dataTable.moment( 'HH:mm DD/MM/YYYY' );
    let list_timetape_table = $('#list_timetape').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.events.getBriefTimeTape',['id'=>$event->theater_of_operations->id,'event_id'=>$event->id])}}",
            "dataSrc": ""
        },
        "order": [[ 0, "desc" ]],
        "paging":   false,
        "info":     false,
        "searching": false
    });

    let list_victims_table = $('#list_victims').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.events.getVictims',['id'=>$event->theater_of_operations->id,'event_id'=>$event->id])}}",
            "dataSrc": ""
        },
        "order": [[ 0, "desc" ]],
        "paging":   false,
        "info":     false,
        "searching": true,
        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": "<a href='#0' data-action='open'>Abrir</a> | <a href='#map' data-action='zoom-in'>Centrar</a>"
        }]
    });

    $('#list_victims tbody').on( 'click', 'a', function () {
        var data = list_victims_table.row($(this).parents('tr')).data();
        let action = $(this).data('action');
        if(action == "zoom-in") {
            map.zoomON(data[4],data[5],15);
        }
        else if(action == "open"){
            openVictim(data[6])
        }
    });

    let list_units_table = $('#list_units').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.events.getUnits',['id'=>$event->theater_of_operations->id,'event_id'=>$event->id])}}",
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
            "defaultContent": "<a href='#0' data-action='open'>Abrir</a> | <a href='#0' data-action='zoom-in'>Centrar</a> "
        }]
    });
    $('#list_units tbody').on( 'click', 'a', function () {
        var data = list_units_table.row($(this).parents('tr')).data();
        let action = $(this).data('action');
        if(action == "zoom-in") {
            map.zoomON(data[5],data[6],15);
        }
        else if(action == "open"){
            openUnit(data[7]);
        }
    });

    let open_victim = -1;
    function openVictim(id) {
        closeAllVictimModals();
        closeAllUnitModals();
        let url = "{{route('theaters_of_operations.events.victims.get',['id'=>$event->theater_of_operations->id,'event_id'=>$event->id,'victim_id'=>'-2'])}}";
        url = url.split("-2").join(id);
        axios.get(url)
        .then(function (response) {
            let template = $("#template_victim").html();
            let event_unit_id = null;
            let event_unit_name = null;
            if(response.data.unit != null) {
                event_unit_id = response.data.theater_of_operations_event_unit_id;
                event_unit_name = response.data.unit.tail_number == null ? response.data.unit.plate : response.data.unit.tail_number;
            }
            let template_data = $("#template_victim_data").html();
            let template_location = $("#template_victim_location").html();
            let template_timings = $("#template_victim_timings").html();
            template = template.split("[[ID]]").join(response.data.id);
            template = template.split("-1").join(response.data.id);
            template = template.split("[[NAME]]").join(response.data.name || "");
            template = template.split("[[AGE]]").join(response.data.age || "");
            template = template.split("[[GENDER]]").join(response.data.gender == null?"":response.data.gender == 0?"Masculino":"Feminino");
            template = template.split("[[SNS]]").join(response.data.sns || "");
            template = template.split("[[EVENT_UNIT_ID]]").join(event_unit_id || "");
            template = template.split("[[EVENT_UNIT_NAME]]").join(event_unit_name || "");
            template = template.split("[[DESTINATION]]").join(response.data.destination || "");
            template = template.split("[[LAT]]").join(response.data.destination_lat || "");
            template = template.split("[[LONG]]").join(response.data.destination_long || "");
            template = template.split("[[STATUSTEXT]]").join(response.data.status_text);
            template = template.split("[[CANCELED_AT]]").join(response.data.canceled_at || "");
            template = template.split("[[DEPARTURE_FROM_SCENE]]").join(response.data.departure_from_scene || "");
            template = template.split("[[ARRIVAL_ON_DESTINATION]]").join(response.data.arrival_on_destination || "");
            template = template.split("[[ASSISTED_ON_SCENE]]").join(response.data.assisted_on_scene || "");
            template = template.split("[[ABANDONED_SCENE]]").join(response.data.abandoned_scene || "");
            template = template.split("[[REFUSED_ASSISTANCE]]").join(response.data.refused_assistance || "");
            template = template.split("[[OBSERVATIONS]]").join(response.data.observations || "");
            template_data = template_data.split("[[ID]]").join(response.data.id);
            template_data = template_data.split("-1").join(response.data.id);
            template_data = template_data.split("[[NAME]]").join(response.data.name || "");
            template_data = template_data.split("[[AGE]]").join(response.data.age || "");
            template_data = template_data.split("[[GENDER]]").join(response.data.gender == null?"":response.data.gender == 0?"Masculino":"Feminino");
            template_data = template_data.split("[[SNS]]").join(response.data.sns || "");
            template_location = template_location.split("-1").join(response.data.id);
            template_location = template_location.split("[[ID]]").join(response.data.id);
            template_location = template_location.split("[[DESTINATION]]").join(response.data.destination || "");
            template_location = template_location.split("[[LAT]]").join(response.data.destination_lat || "");
            template_location = template_location.split("[[LONG]]").join(response.data.destination_long || "");
            template_timings = template_timings.split("-1").join(response.data.id);
            template_timings = template_timings.split("[[ID]]").join(response.data.id);
            template_timings = template_timings.split("[[CANCELED_AT]]").join((response.data.canceled_at || "").replace(" ","T"));
            template_timings = template_timings.split("[[DEPARTURE_FROM_SCENE]]").join((response.data.departure_from_scene || "").replace(" ","T"));
            template_timings = template_timings.split("[[ARRIVAL_ON_DESTINATION]]").join((response.data.arrival_on_destination || "").replace(" ","T"));
            template_timings = template_timings.split("[[ASSISTED_ON_SCENE]]").join((response.data.assisted_on_scene || "").replace(" ","T"));
            template_timings = template_timings.split("[[ABANDONED_SCENE]]").join((response.data.abandoned_scene || "").replace(" ","T"));
            template_timings = template_timings.split("[[REFUSED_ASSISTANCE]]").join((response.data.refused_assistance || "").replace(" ","T"));
            $('#victim .modal-content').html(template);
            $('#victim_data .modal-content').html(template_data);
            $('#victim_location .modal-content').html(template_location);
            $('#victim_timings .modal-content').html(template_timings);
            $("#victim_status").val(response.data.status);
            $("#victim_data_gender").val(response.data.gender);
            if(response.data.status == 0) {
                $(".victim_timings_canceled").show();
            }
            else if(response.data.status == 2) {
                $(".victim_timings_assisted_on_scene").show();
            }
            else if(response.data.status == 3) {
                $(".victim_timings_abandoned_scene").show();
            }
            else if(response.data.status == 4) {
                $(".victim_timings_refused_assistance").show();
            }
            else {
                $(".victim_timings_normal").show();
            }
            if(event_unit_id) {
                $("#event_unit_open").show();
            }
            if(response.data.name == null && response.data.age == null && response.data.sns == null && response.data.theater_of_operations_event_unit_id == null) {
                $("#victim_delete").show();
            }
            open_victim = id;
            $(".modal form").submit(function(e) {
                e.preventDefault(); // avoid to execute the actual submit of the form.

                var form = $(this);
                var url = form.attr('action');

                axios.post(url, form.serialize())
                .then(function (response) {
                    list_victims_table.ajax.reload();
                    openVictim(open_victim);            
                });
            });
            var typingTimer;
            var doneTypingInterval = 250;
            var $input_victim = $('#victim_observations');

            $input_victim.on('blur', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTyping, doneTypingInterval);
            });

            $input_victim.on('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTyping, doneTypingInterval);
            });

            $input_victim.on('paste', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTyping, doneTypingInterval);
            });

            $input_victim.on('focus', function () {
                clearTimeout(typingTimer);
            });

            $input_victim.on('keydown', function () {
                clearTimeout(typingTimer);
            });

            function doneTyping () {
                let observations = $input_victim.val();
                let id = $input_victim.data('id');
                let url = "{{route('theaters_of_operations.events.victims.updateObservations',['id' => $event->theater_of_operations->id, 'event_id' => $event->id, 'victim_id' => '-1' ])}}".replace("-1",id);
                axios.post(url, {
                    observations: observations
                })
                .then(function (response) {
                });
            }
            $('#victim').modal();
        });
    }

    let open_unit = -1;
    function openUnit(id) {
        closeAllVictimModals();
        closeAllUnitModals();
        let url = "{{route('theaters_of_operations.events.units.get',['id'=>$event->theater_of_operations->id,'event_id'=>$event->id,'event_unit_id'=>'-2'])}}";
        url = url.split("-2").join(id);
        axios.get(url)
        .then(function (response) {
            console.log(response.data);
            let template = $("#template_unit").html();
            let template_unit_timings = $("#template_unit_timings").html();
            let name = response.data.unit.tail_number == null ? response.data.unit.plate : response.data.unit.tail_number;
            template = template.split("[[ID]]").join(response.data.id);
            template = template.split("-1").join(response.data.id);
            template = template.split("-2").join(response.data.unit.id);
            template = template.split("[[NAME]]").join(name);
            template = template.split("[[TYPE]]").join(response.data.unit.type || "");
            template = template.split("[[TAIL_NUMBER]]").join(response.data.unit.tail_number || "");
            template = template.split("[[PLATE]]").join(response.data.unit.plate || "");
            template = template.split("[[STRUCTURE]]").join(response.data.unit.structure || "");
            template = template.split("[[STATUSTEXT]]").join(response.data.unit.status_text || ""); 
            template = template.split("[[ACTIVATION]]").join(response.data.timings.activation || ""); 
            template = template.split("[[ON_WAY_TO_SCENE]]").join(response.data.timings.on_way_to_scene || ""); 
            template = template.split("[[ARRIVAL_ON_SCENE]]").join(response.data.timings.arrival_on_scene || ""); 
            template = template.split("[[DEPARTURE_FROM_SCENE]]").join(response.data.timings.departure_from_scene || ""); 
            template = template.split("[[ARRIVAL_ON_DESTINATION]]").join(response.data.timings.arrival_on_destination || ""); 
            template = template.split("[[DEPARTURE_FROM_DESTINATION]]").join(response.data.timings.departure_from_destination || ""); 
            template = template.split("[[AVAILABLE]]").join(response.data.timings.available || "");
            template_unit_timings = template_unit_timings.split("-1").join(response.data.id);
            template_unit_timings = template_unit_timings.split("[[ACTIVATION]]").join((response.data.timings.activation || "").replace(" ","T")); 
            template_unit_timings = template_unit_timings.split("[[ON_WAY_TO_SCENE]]").join((response.data.timings.on_way_to_scene || "").replace(" ","T")); 
            template_unit_timings = template_unit_timings.split("[[ARRIVAL_ON_SCENE]]").join((response.data.timings.arrival_on_scene || "").replace(" ","T")); 
            template_unit_timings = template_unit_timings.split("[[DEPARTURE_FROM_SCENE]]").join((response.data.timings.departure_from_scene || "").replace(" ","T")); 
            template_unit_timings = template_unit_timings.split("[[ARRIVAL_ON_DESTINATION]]").join((response.data.timings.arrival_on_destination || "").replace(" ","T")); 
            template_unit_timings = template_unit_timings.split("[[DEPARTURE_FROM_DESTINATION]]").join((response.data.timings.departure_from_destination || "").replace(" ","T")); 
            template_unit_timings = template_unit_timings.split("[[AVAILABLE]]").join((response.data.timings.available || "").replace(" ","T"));            
            $('#unit .modal-content').html(template);
            $('#unit_timings .modal-content').html(template_unit_timings);
            $("#unit_status").val(response.data.unit.status);
            $('#list_unit_crews').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
                },
                "data": response.data.crews_listing || [],
                "order": [[ 0, "desc" ]],
                "sort":     false,
                "paging":   false,
                "info":     false,
                "searching": false
            });
            let list_unit_victims = $('#list_unit_victims').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
                },
                "data": response.data.victims_listing || [],
                "order": [[ 0, "desc" ]],
                "sort":     false,
                "paging":   false,
                "info":     false,
                "searching": false,
                "columnDefs": [{
                    "targets": -1,
                    "data": null,
                    "defaultContent": "<a href='#0' data-action='open'>Abrir</a>"
                }]
            });
            $('#list_unit_victims tbody').on( 'click', 'a', function () {
                var data = list_unit_victims.row($(this).parents('tr')).data();
                let action = $(this).data('action');
                if(action == "open"){
                    openVictim(data[3]);
                }
            });
            open_unit = id;
            $(".modal form").submit(function(e) {
                e.preventDefault(); // avoid to execute the actual submit of the form.
                
                var form = $(this);
                var url = form.attr('action');
                
                axios.post(url, form.serialize())
                .then(function (response) {
                    list_units_table.ajax.reload();
                    openUnit(open_unit);        
                });
            });
            $('#unit').modal();
        });
    }

    function closeAllVictimModals() {
        $('#victim').modal('hide');
        $('#victim_data').modal('hide');
        $('#victim_location').modal('hide');
        $('#victim_timings').modal('hide');
    }

    function closeAllUnitModals() {
        $('#unit').modal('hide');
        $('#unit_timings').modal('hide');
    }
</script>
@if(!$event->isFinished())
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
        axios.post("{{route('theaters_of_operations.events.updateObservations',['id' => $event->theater_of_operations->id, 'event_id' => $event->id])}}", {
            observations: observations
        })
        .then(function (response) {
        });
    }
</script>
@endif
@endsection