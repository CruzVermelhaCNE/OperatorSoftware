@extends('theaters_of_operations/layouts/panel')

@if ($theater_of_operations)
@section('pageTitle', 'Editar Teatro de Operações')
@else
@section('pageTitle', 'Criar Teatro de Operações')
@endif

@section('style')
@parent
@endsection

@section('content')
<div class="container">
    <h2>Teatro de Operações</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="POST" action="{{$theater_of_operations?route('theaters_of_operations.edit',['id'=>$theater_of_operations->id]):route('theaters_of_operations.create')}}">
        @csrf
        <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" placeholder="Nome" name="name" @if ($theater_of_operations) value="{{$theater_of_operations->name}}" @else value="{{ old('name') }}" @endif>
        </div>
        <div class="form-group">
            <label>Tipo</label>
            <select class="form-control" name="type">
                <option value="Incêndio" @if ($theater_of_operations) @if($theater_of_operations->type == "Incêndio") selected @endif @else @if (old("type") == "Incêndio") selected @endif @endif>Incêndio</option>
            </select>
        </div>
        <div class="form-group">
            <label>Canal de Criação</label>
            <select class="form-control" name="creation_channel">
                <option value="CNE" @if ($theater_of_operations) @if($theater_of_operations->creation_channel == "CNE") selected @endif @else @if (old("creation_channel") == "CNE") selected @endif @endif>CNE</option>
                <option value="CNOS" @if ($theater_of_operations) @if($theater_of_operations->creation_channel == "CNOS") selected @endif @else @if (old("creation_channel") == "CNOS") selected @endif @endif>CNOS</option>
            </select>
        </div>
        <div class="form-group">
            <label>Localização</label>
            <input type="text" class="form-control" placeholder="Localização" name="location" @if ($theater_of_operations) value="{{$theater_of_operations->location}}" @else value="{{ old('location') }}" @endif>
        </div>
        <div class="form-group">
            <label>Latitude</label>
            <input type="text" class="form-control" placeholder="Latitude" name="lat" @if ($theater_of_operations) value="{{$theater_of_operations->lat}}" @else value="{{ old('lat') }}" @endif>
        </div>
        <div class="form-group">
            <label>Longitude</label>
            <input type="text" class="form-control" placeholder="Longitude" name="long" @if ($theater_of_operations) value="{{$theater_of_operations->long}}" @else value="{{ old('long') }}" @endif>
        </div>
        <div class="form-group">
            <label>Nível</label>
            <select class="form-control" name="level">
                <option value="Nivel 1" @if ($theater_of_operations) @if($theater_of_operations->level == "Nivel 1") selected @endif @else @if (old("level") == "Nivel 1") selected @endif @endif>Nivel 1</option>
                <option value="Nivel 2" @if ($theater_of_operations) @if($theater_of_operations->level == "Nivel 2") selected @endif @else @if (old("level") == "Nivel 2") selected @endif @endif>Nivel 2</option>
                <option value="Nivel 3" @if ($theater_of_operations) @if($theater_of_operations->level == "Nivel 3") selected @endif @else @if (old("level") == "Nivel 3") selected @endif @endif>Nivel 3</option>
            </select>
        </div>
        <div class="form-group">
            <label>Observações</label>
            <textarea class="form-control" placeholder="Observações" name="observations" rows="3">@if ($theater_of_operations){{$theater_of_operations->observations}} @else {{ old('observations') }} @endif</textarea>
        </div>
        <div class="form-group">
            <label>Número CDOS (Opcional)</label>
            <input type="text" class="form-control" placeholder="Número CDOS" name="cdos" @if ($theater_of_operations) value="{{$theater_of_operations->cdos}}" @else value="{{ old('location') }}" @endif>
        </div>
        <button type="submit" class="btn btn-secondary">{{$theater_of_operations?"Guardar":"Criar"}}</button>
        @if($theater_of_operations)
        <a class="btn btn-info" href="{{route('theaters_of_operations.single',$theater_of_operations->id)}}">Voltar</a>
        @else
        <a class="btn btn-info" href="{{route('theaters_of_operations.list')}}">Cancelar</a>
        @endif
    </form>
</div>
@endsection

@section('javascript')
@parent
@endsection