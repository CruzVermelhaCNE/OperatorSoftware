@extends('theaters_of_operations/layouts/panel')

@section('pageTitle', 'Unidade '.$unit->tail_number?$unit->tail_number:$unit->plate)

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
        <h2>Unidade - {{$unit->tail_number?$unit->tail_number:$unit->plate}}</h2>
        <h4>{{$unit->type}} - {{$unit->structure}}</h4>
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
            <p>Tipo: {{$unit->type}}</p>
            <p>Matricula: {{$unit->plate?$unit->plate:"N/A"}}</p>
            <p>Nº de Cauda: {{$unit->tail_number?$unit->tail_number:"N/A"}}</p>
            <p>Status: {{$unit->status_text}}</p>
            <p>Localização: {{$unit->deployment}}</p>
            @if (!$unit->isDemobilized())
            <p>No TO {{$unit->created_at->locale('pt_PT')->diffForHumans()}}</p>
            <a href="{{route('theaters_of_operations.units.demobilize',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id])}}"
                class="btn btn-danger">Desmobilizar Unidade</a>
            @else
            <p>Esteve no TO {{$unit->deleted_at->locale('pt_PT')->diffForHumans($unit->created_at)}}</p>
            @endif
            <a href="{{route('theaters_of_operations.single',["id" => $unit->theater_of_operations->id])}}"
                class="btn btn-info">Abrir TO</a>
            @if(!$unit->theater_of_operations->trashed())
            @if(!$unit->theater_of_operations->trashed())
            <a href="{{route('theaters_of_operations.units.edit',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id])}}"
                class="btn btn-dark">Editar</a>
            @endif
            @if (!$unit->isDemobilized())
            <h2>Destacar</h2>
            <form
                action="{{route('theaters_of_operations.units.assignToPOI',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id])}}"
                method="POST">
                @csrf
                <div class="input-group">
                    <select class="custom-select" id="list_pois" name="poi_id">
                        <option @if ($unit->theater_of_operations_poi_id == null) selected @endif>Escolher
                        </option>
                        @foreach ($unit->theater_of_operations->pois as $poi)
                        <option value="{{$poi->id}}" @if ($unit->theater_of_operations_poi_id == $poi->id) selected
                            @endif>{{$poi->name}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-warning" type="submit">Destacar</button>
                    </div>
                </div>
            </form>
            <h2>Status</h2>
            @if ($unit->active_event)
            <a href="{{route('theaters_of_operations.events.single',["id" => $unit->theater_of_operations->id, "event_id" => $unit->active_event->id])}}"
                class="btn btn-info">Abrir Ocorrência</a>
            @else
            <form
                action="{{route('theaters_of_operations.units.updateStatus',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id])}}"
                method="POST">
                @csrf
                <div class="input-group">
                    <select class="custom-select" name="status">
                        <option value="0" @if ($unit->status == 0) selected @endif>INOP</option>
                        <option value="1" @if ($unit->status == 1) selected @endif>Na base</option>
                        <option value="7" @if ($unit->status == 7) selected @endif>A Caminho da Base</option>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Atualizar</button>
                    </div>
                </div>
            </form>
            @endif
            @endif
            @endif
        </div>
        <div class="col-sm-6">
            <div id="map"></div>
            <a href="#map" onclick="centerMap()" class="btn btn-info">Centrar na Unidade</a>
        </div>
    </div>
    <div class="row">
        <h4 style="text-align: center;width:100vw;">Fita do Tempo (Resumo) - <a href="#0">Ver Todo</a></h4>
        <table id="list_timetape" style="width:100%" class="table table-sm table-dark table-striped table-bordered">
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
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th>Nome</th>
                    <th>Contacto</th>
                    <th>Idade</th>
                    <th>Formação</th>
                    <th>Observações</th>
                    <th>Ações</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="row">
        <h4 style="text-align: center; width:100vw">Comunicações</h4>
        <table id="list_communication_channels" style="width:100%"
            class="table table-sm table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Observações</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th>Tipo</th>
                    <th>Observações</th>
                    <th>Ações</th>
                </tr>
            </tfoot>
        </table>
        @if(!$unit->isDemobilized())
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#communications_create">Adicionar
            Comunicações</button>
        @endif
    </div>
    <div class="row">
        <h4 style="text-align: center; width:100vw">Observações</h4>
        <div class="form-group" style="width:100vw">
            @if(!$unit->isDemobilized())
            <textarea class="form-control" placeholder="Observações" id="observations"
                rows="3">{{$unit->observations}}</textarea>
            @else
            <p>{{$unit->observations}}</p>
            @endif
        </div>
    </div>
</div>
<div id="communications_create" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Comunicações</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form
                action="{{route('theaters_of_operations.units.createCommunicationChannel',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id])}}"
                method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select class="form-control" name="type">
                                    <option value="SIRESP" selected>SIRESP</option>
                                    <option value="VHF">VHF</option>
                                    <option value="UHF">UHF</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Observações</label>
                                <textarea class="form-control" placeholder="Observações" name="observations"
                                    rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="communications_edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark">
        </div>
    </div>
</div>
@endsection

@section('javascript')
@parent
<script type="text/template" id="template_communications_edit">
<div class="modal-header">
    <h5 class="modal-title">Editar Comunicações</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<form
    action="{{route('theaters_of_operations.units.communication_channels.update',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id, "communication_channel_id" => "-1"])}}"
    method="POST">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label>Tipo</label>
                    <select class="form-control" name="type" id="communications_edit_type">
                        <option value="SIRESP" selected>SIRESP</option>
                        <option value="VHF">VHF</option>
                        <option value="UHF">UHF</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label>Observações</label>
                    <textarea class="form-control" placeholder="Observações" name="observations" rows="3">[[OBSERVATIONS]]</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#0" onclick="removeCommunicationChannel(-1)" class="btn btn-danger">Remover</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
</script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.20/sorting/datetime-moment.js"></script>
<script src="{{ mix('js/map.js') }}"></script>
<script>
    let map = new Map({{$unit->theater_of_operations->id}}, 'map', 11, {{$unit->lat}}, {{$unit->long}});
    $.fn.dataTable.moment( 'HH:mm DD/MM/YYYY' );
    let list_timetape_table = $('#list_timetape').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.units.getBriefTimeTape',['id'=>$unit->theater_of_operations->id,'unit_id'=>$unit->id])}}",
            "dataSrc": ""
        },
        "order": [[ 0, "desc" ]],
        "paging":   false,
        "info":     false,
        "searching": false
    });

    let list_crews_table = $('#list_crews').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.units.getCrews',['id'=>$unit->theater_of_operations->id,'unit_id'=>$unit->id])}}",
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
            "defaultContent": "<a href='#0' data-action='open'>Abrir</a>"
        }]
    });
    $('#list_crews tbody').on( 'click', 'a', function () {
        var data = list_crews_table.row($(this).parents('tr')).data();
        let action = $(this).data('action');
        if(action == "open"){
            window.open("{{route('theaters_of_operations.crews.single',['id'=>$unit->theater_of_operations->id,'crew_id' => ''])}}/"+data[9]);
        }
    });

    let list_communication_channels_table = $('#list_communication_channels').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('theaters_of_operations.units.getCommunicationChannels',['id'=>$unit->theater_of_operations->id,'unit_id'=>$unit->id])}}",
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
            "defaultContent": "<a href='#0' data-action='edit'>Editar</a>"
        }]
    });
    $('#list_communication_channels tbody').on( 'click', 'a', function () {
        var data = list_communication_channels_table.row($(this).parents('tr')).data();
        let action = $(this).data('action');
        if(action == "edit"){
            editCommunicationChannel(data[2]);
        }
    });

    $("#communications_create form").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');

        axios.post(url, form.serialize())
        .then(function (response) {
            $("#communications_create").modal('hide');
            list_communication_channels_table.ajax.reload();  
        });
    });

    let open_communication_channel_id = -1;
    function editCommunicationChannel(id) {
        axios.get("{{route('theaters_of_operations.units.communication_channels.get',['id'=>$unit->theater_of_operations->id,'unit_id'=>$unit->id,'communication_channel_id'=>''])}}/"+id)
        .then(function (response) {
            console.log(response.data);
            let template = $("#template_communications_edit").html();
            template = template.split("-1").join(response.data.id);
            template = template.split("[[TYPE]]").join(response.data.type || "");
            template = template.split("[[OBSERVATIONS]]").join(response.data.observations || "");  
            $('#communications_edit .modal-content').html(template);
            $("#communications_edit_type").val(response.data.type);
            open_communication_channel_id = id;
            $("#communications_edit form").submit(function(e) {
                e.preventDefault(); // avoid to execute the actual submit of the form.
                
                var form = $(this);
                var url = form.attr('action');
                
                axios.post(url, form.serialize())
                .then(function (response) {
                    $("#communications_edit").modal('hide');
                    list_communication_channels_table.ajax.reload();
                });
            });
            $('#communications_edit').modal();
        });
    }

    function removeCommunicationChannel(id) {
        let url = "{{route('theaters_of_operations.units.communication_channels.remove',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id, "communication_channel_id" => "-1"])}}";
        url = url.split("-1").join(id);
        axios.get(url).then(function (response) {
            $("#communications_edit").modal('hide');
            list_communication_channels_table.ajax.reload();
        });
    }


    function centerMap() {
       map.recenter();
    }

</script>
@if(!$unit->isDemobilized())
<script>
    var typingTimer;
    var doneTypingInterval = 1000;
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
        axios.post("{{route('theaters_of_operations.units.updateObservations',['id' => $unit->theater_of_operations->id, 'unit_id' => $unit->id])}}", {
            observations: observations
        })
        .then(function (response) {
        });
    }
</script>
@endif
@endsection