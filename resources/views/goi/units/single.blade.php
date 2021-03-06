@extends('goi/layouts/panel')

@section('pageTitle', 'Meio '.$unit->tail_number?$unit->tail_number:$unit->plate)

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
        <h2>Meio - {{$unit->tail_number?$unit->tail_number:$unit->plate}}</h2>
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
            <p>No TO há {{$unit->created_at->locale('pt_PT')->diffInHours()}}h{{$unit->created_at->locale('pt_PT')->diff()->format('%Im%Ss')}}</p>
            <a href="{{route('goi.units.demobilize',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id])}}"
                class="btn btn-danger">Desmobilizar Meio</a>
            @else
            <p>Desmobilizado: {{$unit->demobilized_at}}</p>
            <p>Esteve mobilizado {{$unit->demobilized_at->locale('pt_PT')->diffInHours($unit->created_at)}}h{{$unit->demobilized_at->locale('pt_PT')->diff($unit->created_at)->format('%Im%Ss')}}</p>
            @endif
            <a href="{{route('goi.single',["id" => $unit->theater_of_operations->id])}}"
                class="btn btn-info">Abrir TO</a>
            @if(!$unit->theater_of_operations->trashed())
            @if(!$unit->theater_of_operations->trashed())
            <a href="{{route('goi.units.edit',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id])}}"
                class="btn btn-dark">Editar</a>
            @endif
            @if (!$unit->isDemobilized())
            <h2>Destacar</h2>
            <form
                action="{{route('goi.units.assignToPOI',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id])}}"
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
            <a href="{{route('goi.events.single',["id" => $unit->theater_of_operations->id, "event_id" => $unit->active_event->id])}}"
                class="btn btn-info">Abrir Ocorrência</a>
            @else
            <form
                action="{{route('goi.units.updateStatus',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id])}}"
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
            <a href="#map" onclick="centerMap()" class="btn btn-info">Centrar na Meio</a>
        </div>
    </div>
    <div class="row">
        <h4 style="text-align: center;width:100vw;">Fita do Tempo (Resumo) - <a href="{{route('goi.timetape.index', ['type'=>'unit','object'=>$unit->id])}}">Ver Todo</a></h4>
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
        </table>
        @if(!$unit->isDemobilized())
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#communications_create">Adicionar
            Comunicações</button>
        @endif
    </div>
    <div class="row">
        <h4 style="text-align: center; width:100vw">Georeferênciação</h4>
        <table id="list_geotracking" style="width:100%" class="table table-sm table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th>Sistema</th>
                    <th>ID Externo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        @if(!$unit->isDemobilized())
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#geotracking_create">Adicionar
            Georeferênciação</button>
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
                action="{{route('goi.units.createCommunicationChannel',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id])}}"
                method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select class="form-control" name="type">
                                    <option value="Telemóvel" selected>Telemóvel</option>
                                    <option value="SIRESP">SIRESP</option>
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
<div id="geotracking_create" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Georeferênciação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form
                action="{{route('goi.units.createGeotracking',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id])}}"
                method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Sistema</label>
                                <select class="form-control" name="system">
                                    <option value="Wialon" selected>Wialon</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>External ID</label>
                                <input class="form-control" type="text" placeholder="External ID" name="external_id" />
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
<div id="geotracking_edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
    action="{{route('goi.units.communication_channels.update',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id, "communication_channel_id" => "-1"])}}"
    method="POST">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label>Tipo</label>
                    <select class="form-control" name="type" id="communications_edit_type">
                        <option value="Telemóvel" selected>Telemóvel</option>
                        <option value="SIRESP">SIRESP</option>
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

<script type="text/template" id="template_geotracking_edit">
    <div class="modal-header">
        <h5 class="modal-title">Editar Georeferênciação</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form
        action="{{route('goi.units.geotracking.update',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id, "geotracking_id" => "-1"])}}"
        method="POST">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Tipo</label>
                        <select class="form-control" name="system" id="geotracking_edit_system">
                            <option value="Wialon" selected>Wialon</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>ID Externo</label>
                        <input class="form-control" type="text" placeholder="ID Externo" name="external_id" value="[[EXTERNAL_ID]]"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#0" onclick="removeGeotracking(-1)" class="btn btn-danger">Remover</a>
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
    let map = new Map({{$unit->theater_of_operations->id}}, 'map', 15, {{$unit->lat}}, {{$unit->long}});
    $.fn.dataTable.moment( 'HH:mm DD/MM/YYYY' );
    let list_timetape_table = $('#list_timetape').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('goi.units.getBriefTimeTape',['id'=>$unit->theater_of_operations->id,'unit_id'=>$unit->id])}}",
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
            "url": "{{route('goi.units.getCrews',['id'=>$unit->theater_of_operations->id,'unit_id'=>$unit->id])}}",
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
            let url = "{{route('goi.crews.single',['id'=>$unit->theater_of_operations->id,'crew_id' => '-1'])}}";
            url = url.split("-1").join(data[9]);
            location.replace(url);
        }
    });

    let list_communication_channels_table = $('#list_communication_channels').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('goi.units.getCommunicationChannels',['id'=>$unit->theater_of_operations->id,'unit_id'=>$unit->id])}}",
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
        let url = "{{route('goi.units.communication_channels.get',['id'=>$unit->theater_of_operations->id,'unit_id'=>$unit->id,'communication_channel_id'=>'-1'])}}";
            url = url.split("-1").join(id);
        axios.get(url)
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
        let url = "{{route('goi.units.communication_channels.remove',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id, "communication_channel_id" => "-1"])}}";
        url = url.split("-1").join(id);
        axios.get(url).then(function (response) {
            $("#communications_edit").modal('hide');
            list_communication_channels_table.ajax.reload();
        });
    }

    let list_geotracking_table = $('#list_geotracking').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese.json"
        },
        "ajax": {
            "url": "{{route('goi.units.getGeotracking',['id'=>$unit->theater_of_operations->id,'unit_id'=>$unit->id])}}",
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
    $('#list_geotracking tbody').on( 'click', 'a', function () {
        var data = list_geotracking_table.row($(this).parents('tr')).data();
        let action = $(this).data('action');
        if(action == "edit"){
            editGeotracking(data[2]);
        }
    });

    $("#geotracking_create form").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');

        axios.post(url, form.serialize())
        .then(function (response) {
            $("#geotracking_create").modal('hide');
            list_geotracking_table.ajax.reload();
        });
    });
    

    let open_geotracking_id = -1;
    function editGeotracking(id) {
        let url = "{{route('goi.units.geotracking.get',['id'=>$unit->theater_of_operations->id,'unit_id'=>$unit->id,'geotracking_id'=>'-1'])}}";
            url = url.split("-1").join(id);
        axios.get(url)
        .then(function (response) {
            console.log(response.data);
            let template = $("#template_geotracking_edit").html();
            template = template.split("-1").join(response.data.id);
            template = template.split("[[SYSTEM]]").join(response.data.system || "");
            template = template.split("[[EXTERNAL_ID]]").join(response.data.external_id || "");  
            $('#geotracking_edit .modal-content').html(template);
            $("#geotracking_edit_system").val(response.data.system);
            open_geotracking_id = id;
            $("#geotracking_edit form").submit(function(e) {
                e.preventDefault(); // avoid to execute the actual submit of the form.
                
                var form = $(this);
                var url = form.attr('action');
                
                axios.post(url, form.serialize())
                .then(function (response) {
                    $("#geotracking_edit").modal('hide');
                    list_geotracking_table.ajax.reload();
                });
            });
            $('#geotracking_edit').modal();
        });
    }

    function removeGeotracking(id) {
        let url = "{{route('goi.units.geotracking.remove',["id" => $unit->theater_of_operations->id, "unit_id" => $unit->id, "geotracking_id" => "-1"])}}";
        url = url.split("-1").join(id);
        axios.get(url).then(function (response) {
            $("#geotracking_edit").modal('hide');
            list_geotracking_table.ajax.reload();
        });
    }


    function centerMap() {
       map.recenter();
    }

</script>
@if(!$unit->isDemobilized())
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
        axios.post("{{route('goi.units.updateObservations',['id' => $unit->theater_of_operations->id, 'unit_id' => $unit->id])}}", {
            observations: observations
        })
        .then(function (response) {
        });
    }
</script>
@endif
@endsection