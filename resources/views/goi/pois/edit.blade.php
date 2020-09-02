@extends('theaters_of_operations/layouts/panel')

@if ($poi)
@section('pageTitle', 'Editar POI')
@else
@section('pageTitle', 'Criar POI')
@endif

@section('style')
@parent
@endsection

@section('content')
<div class="container">
    <h2>Ponto de Interesse</h2>
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
        action="{{$poi?route('goi.pois.edit',["id"=>$theater_of_operations->id,"poi_id"=>$poi->id]):route('goi.pois.create',$theater_of_operations->id)}}">
        @else
        @endif
        @csrf
        @if ($poi)
        <input type="hidden" name="id" value="{{$poi->id}}">

        @else
        @if ($theater_of_operations)
        <input type="hidden" name="theater_of_operations_id" value="{{$theater_of_operations->id}}">
        @else
        <input type="hidden" name="theater_of_operations_sector_id" value="{{$theater_of_operations_sector->id}}">
        @endif
        @endif
        <div class="form-group">
            <label>Nome</label>
        <input type="text" class="form-control" placeholder="Nome" name="name" @if ($poi) value="{{$poi->name}}" @else value="{{old('name')}}"
                @endif>
        </div>
        <div class="form-group">
            <label>Simbolo</label>
            <select class="form-control" name="symbol">
                <option value="POI Geral" @if ($poi) @if($poi->symbol == "POI Geral") selected @endif @else @if(old('symbol') == "POI Geral") selected @endif @endif>POI Geral</option>
                <option value="PC" @if ($poi) @if($poi->symbol == "PC") selected @endif @else @if(old('symbol') == "PC") selected @endif @endif>PC</option>
                <option value="ZCAP" @if ($poi) @if($poi->symbol == "ZCAP") selected @endif @else @if(old('symbol') == "ZCAP") selected @endif @endif>ZCAP</option>
                <option value="ZCR" @if ($poi) @if($poi->symbol == "ZCR") selected @endif @else @if(old('symbol') == "ZCR") selected @endif @endif>ZCR</option>
                <option value="Logística" @if ($poi) @if($poi->symbol == "Logística") selected @endif @else @if(old('symbol') == "Logística") selected @endif @endif>Logística</option>
                <option value="PMA" @if ($poi) @if($poi->symbol == "PMA") selected @endif @else @if(old('symbol') == "PMA") selected @endif @endif>PMA</option>
                <option value="Antena" @if ($poi) @if($poi->symbol == "Antena") selected @endif @else @if(old('symbol') == "Antena") selected @endif @endif>Antena</option>
                <option value="Satélite" @if ($poi) @if($poi->symbol == "Satélite") selected @endif @else @if(old('symbol') == "Satélite") selected @endif @endif>Satélite</option>
            </select>
        </div>
        <div class="form-group">
            <label>Observações</label>
            <textarea class="form-control" placeholder="Observações" name="observations"
                rows="3">@if ($poi){{$poi->observations}} @else {{old('observations')}} @endif</textarea>
        </div>
        <div class="form-group">
            <label>Localização</label>
            <input type="text" class="form-control" placeholder="Localização" name="location" @if ($poi)
                value="{{$poi->location}}" @else value="{{old('location')}}" @endif>
        </div>
        <div class="form-group">
            <label>Latitude</label>
            <input type="text" class="form-control" placeholder="Latitude" name="lat" @if ($poi) value="{{$poi->lat}}" @else value="{{old('lat')}}"
                @endif>
        </div>
        <div class="form-group">
            <label>Longitude</label>
            <input type="text" class="form-control" placeholder="Longitude" name="long" @if ($poi)
                value="{{$poi->long}}" @else value="{{old('long')}}" @endif>
        </div>
        <button type="submit" class="btn btn-secondary">{{$poi?"Guardar":"Criar"}}</button>
        @if ($theater_of_operations)
        <a class="btn btn-info" href="{{route('goi.single',$theater_of_operations->id)}}">Voltar</a>
        @if($poi)
        <a class="btn btn-danger"
            href="{{route('goi.pois.remove',["id"=>$theater_of_operations->id,"poi_id"=>$poi->id])}}">Apagar</a>
        @endif
        @else
        @endif
    </form>
</div>
@endsection

@section('javascript')
@parent
@endsection