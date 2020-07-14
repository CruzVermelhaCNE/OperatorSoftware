@extends('theaters_of_operations/layouts/panel')

@if ($event)
@section('pageTitle', 'Editar Ocorrência')
@else
@section('pageTitle', 'Criar Ocorrência')
@endif

@section('style')
@parent
@endsection

@section('content')
<div class="container">
    <h2>Ocorrência</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if ($theater_of_operations)
    <form method="POST"
        action="{{$event?route('theaters_of_operations.events.edit',["id"=>$theater_of_operations->id,"event_id"=>$event->id]):route('theaters_of_operations.events.create',$theater_of_operations->id)}}">
        @else
        @endif
        @csrf
        @if ($event)
        <input type="hidden" name="id" value="{{$event->id}}">
        @else
        @if ($theater_of_operations)
        <input type="hidden" name="theater_of_operations_id"
            value="{{($theater_of_operations)?$theater_of_operations->id:$theater_of_operations_sector->theater_of_operations_id}}">
        @endif
        @endif
        <div class="form-group">
            <label>CODU</label>
            <input type="text" class="form-control" placeholder="CODU" name="codu" @if ($event) value="{{$event->codu}}" @else value="{{old('codu')}}"
                @endif>
        </div>
        <div class="form-group">
            <label>CDOS</label>
            <input type="text" class="form-control" placeholder="CDOS" name="cdos" @if ($event) value="{{$event->cdos}}" @else value="{{old('cdos')}}"
                @endif>
        </div>
        <div class="form-group">
            <label>Tipo</label>
            <select class="form-control" name="type" id="type_selector">
                <option value="ACD" @if ($event) @if($event->type == "ACD") selected @endif @else @if(old('type') == "ACD") selected @endif @endif>ACD - Acidente</option>
                <option value="AEC" @if ($event) @if($event->type == "AEC") selected @endif @else @if(old('type') == "AEC") selected @endif @endif>AEC - Alteração Estado de Consciência</option>
                <option value="AFO" @if ($event) @if($event->type == "AFO") selected @endif @else @if(old('type') == "AFO") selected @endif @endif>AFO - Afogamento</option>
                <option value="AGR" @if ($event) @if($event->type == "AGR") selected @endif @else @if(old('type') == "AGR") selected @endif @endif>AGR - Agressão</option>
                <option value="ALR" @if ($event) @if($event->type == "ALR") selected @endif @else @if(old('type') == "ALR") selected @endif @endif>ALR - Alergias</option>
                <option value="CAP" @if ($event) @if($event->type == "CAP") selected @endif @else @if(old('type') == "CAP") selected @endif @endif>CAP - CAPCI</option>
                <option value="CDM" @if ($event) @if($event->type == "CDM") selected @endif @else @if(old('type') == "CDM") selected @endif @endif>CDM - CODU MAR</option>
                <option value="CEF" @if ($event) @if($event->type == "CEF") selected @endif @else @if(old('type') == "CEF") selected @endif @endif>CEF - Cefaleias</option>
                <option value="CHE" @if ($event) @if($event->type == "CHE") selected @endif @else @if(old('type') == "CHE") selected @endif @endif>CHE - Cheia</option>
                <option value="CNV" @if ($event) @if($event->type == "CNV") selected @endif @else @if(old('type') == "CNV") selected @endif @endif>CNV - Convulsões</option>
                <option value="CPX" @if ($event) @if($event->type == "CPX") selected @endif @else @if(old('type') == "CPX") selected @endif @endif>CPX - Ocorrências Complexas</option>
                <option value="CRI" @if ($event) @if($event->type == "CRI") selected @endif @else @if(old('type') == "CRI") selected @endif @endif>CRI - Criança</option>
                <option value="DAU" @if ($event) @if($event->type == "DAU") selected @endif @else @if(old('type') == "DAU") selected @endif @endif>DAU - Dor Abdominal</option>
                <option value="DCT" @if ($event) @if($event->type == "DCT") selected @endif @else @if(old('type') == "DCT") selected @endif @endif>DCT - Dor Costas</option>
                <option value="DIA" @if ($event) @if($event->type == "DIA") selected @endif @else @if(old('type') == "DIA") selected @endif @endif>DIA - Diabetes</option>
                <option value="DIS" @if ($event) @if($event->type == "DIS") selected @endif @else @if(old('type') == "DIS") selected @endif @endif>DIS - Dispneia</option>
                <option value="DMS" @if ($event) @if($event->type == "DMS") selected @endif @else @if(old('type') == "DMS") selected @endif @endif>DMS - Dor Membros</option>
                <option value="DPN" @if ($event) @if($event->type == "DPN") selected @endif @else @if(old('type') == "DPN") selected @endif @endif>DPN - Dispneia</option>
                <option value="DTC" @if ($event) @if($event->type == "DTC") selected @endif @else @if(old('type') == "DTC") selected @endif @endif>DTC - Dor Torácica</option>
                <option value="EVA" @if ($event) @if($event->type == "EVA") selected @endif @else @if(old('type') == "EVA") selected @endif @endif>EVA - Evacuação</option>
                <option value="GGR" @if ($event) @if($event->type == "GGR") selected @endif @else @if(old('type') == "GGR") selected @endif @endif>GGR - Gravidez</option>
                <option value="HEM" @if ($event) @if($event->type == "HEM") selected @endif @else @if(old('type') == "HEM") selected @endif @endif>HEM - Hemorragia</option>
                <option value="IND" @if ($event) @if($event->type == "IND") selected @endif @else @if(old('type') == "IND") selected @endif @endif>IND - Indiferenciado</option>
                <option value="NEG" @if ($event) @if($event->type == "NEG") selected @endif @else @if(old('type') == "NEG") selected @endif @endif>NEG - Conflitos Legais</option>
                <option value="ONG" @if ($event) @if($event->type == "ONG") selected @endif @else @if(old('type') == "ONG") selected @endif @endif>ONG - Olhos, Nariz, Garganta</option>
                <option value="OVA" @if ($event) @if($event->type == "OVA") selected @endif @else @if(old('type') == "OVA") selected @endif @endif>OVA - Obstrução Via Aérea</option>
                <option value="PAD" @if ($event) @if($event->type == "PAD") selected @endif @else @if(old('type') == "PAD") selected @endif @endif>PAD - Pedido Apoio Diferenciado</option>
                <option value="PAR" @if ($event) @if($event->type == "PAR") selected @endif @else @if(old('type') == "PAR") selected @endif @endif>PAR - Parto</option>
                <option value="PCR" @if ($event) @if($event->type == "PCR") selected @endif @else @if(old('type') == "PCQ") selected @endif @endif>PCR - Paragem cardiorrespiratória</option>
                <option value="PSQ" @if ($event) @if($event->type == "PSQ") selected @endif @else @if(old('type') == "PSQ") selected @endif @endif>PSQ - Psiquiatria</option>
                <option value="QEL" @if ($event) @if($event->type == "QEL") selected @endif @else @if(old('type') == "QEL") selected @endif @endif>QEL - Queimadura/Eletrocussão</option>
                <option value="TOX" @if ($event) @if($event->type == "TOX") selected @endif @else @if(old('type') == "TOX") selected @endif @endif>TOX - Intoxicação</option>
                <option value="TRA" @if ($event) @if($event->type == "TRA") selected @endif @else @if(old('type') == "TRA") selected @endif @endif>TRA - Trauma</option>
            </select>
        </div>
        <div class="form-group">
            <label>Observações</label>
            <textarea class="form-control" placeholder="Observações" name="observations"
                rows="3">@if ($event){{$event->observations}} @else {{old('observations')}}@endif</textarea>
        </div>
        <div class="form-group">
            <label>Localização</label>
            <input type="text" class="form-control" placeholder="Localização" name="location" @if ($event)
                value="{{$event->location}}" @else value="{{old('location')}}" @endif>
        </div>
        <div class="form-group">
            <label>Latitude</label>
            <input type="text" class="form-control" placeholder="Latitude" name="lat" @if ($event)
                value="{{$event->lat}}" @else value="{{old('lat')}}" @endif>
        </div>
        <div class="form-group">
            <label>Longitude</label>
            <input type="text" class="form-control" placeholder="Longitude" name="long" @if ($event)
                value="{{$event->long}}" @else value="{{old('long')}}" @endif>
        </div>
        <button type="submit" class="btn btn-secondary">{{$event?"Guardar":"Criar"}}</button>
        @if ($theater_of_operations)        
        @if($event)
        <a class="btn btn-info" href="{{route('theaters_of_operations.events.single',["id"=>$theater_of_operations->id,"event_id" => $event->id])}}">Voltar</a>
        @else
        <a class="btn btn-info" href="{{route('theaters_of_operations.single',$theater_of_operations->id)}}">Voltar</a>
        @endif
        @else
        @endif
    </form>
</div>
@endsection

@section('javascript')
@parent
<script>
    $(document).ready(function() {
        $('#type_selector').select2({
            theme: 'bootstrap4',
        });
    });
</script>
@endsection