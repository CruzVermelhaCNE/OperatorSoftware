@extends('theaters_of_operations/layouts/panel')

@if ($unit)
@section('pageTitle', 'Editar Meio')
@else
@section('pageTitle', 'Criar Meio')
@endif

@section('style')
@parent
@endsection

@section('content')
<div class="container">
    <h2>Meio</h2>
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
        action="{{$unit?route('theaters_of_operations.units.edit',["id"=>$theater_of_operations->id,"unit_id"=>$unit->id]):route('theaters_of_operations.units.create',$theater_of_operations->id)}}">
        @else
        @endif
        @csrf
        @if ($unit)
        <input type="hidden" name="id" value="{{$unit->id}}">
        @else
        @if ($theater_of_operations)
        <input type="hidden" name="theater_of_operations_id"
            value="{{($theater_of_operations)?$theater_of_operations->id:$theater_of_operations_sector->theater_of_operations_id}}">
        @endif
        @endif
        <div class="form-group">
            <label>Tipo</label>
            <select class="form-control" name="type">
                <option value="A1" @if ($unit) @if($unit->type == "A1") selected @endif @else @if(old('type') == "A1")
                    selected @endif @endif>A1</option>
                <option value="A2" @if ($unit) @if($unit->type == "A2") selected @endif @else @if(old('type') == "A2")
                    selected @endif @endif>A2</option>
                <option value="B" @if ($unit) @if($unit->type == "B") selected @endif @else @if(old('type') == "B")
                    selected @endif @endif>B</option>
                <option value="C" @if ($unit) @if($unit->type == "C") selected @endif @else @if(old('type') == "C")
                    selected @endif @endif>C</option>
                <option value="VDTD" @if ($unit) @if($unit->type == "VDTD") selected @endif @else @if(old('type') ==
                    "VDTD") selected @endif @endif>VDTD</option>
                <option value="TL" @if ($unit) @if($unit->type == "TL") selected @endif @else @if(old('type') == "TL")
                    selected @endif @endif>TL</option>
                <option value="CC" @if ($unit) @if($unit->type == "CC") selected @endif @else @if(old('type') == "CC")
                    selected @endif @endif>CC</option>
                <option value="CO" @if ($unit) @if($unit->type == "CO") selected @endif @else @if(old('type') == "CO")
                    selected @endif @endif>CO</option>
                <option value="LO" @if ($unit) @if($unit->type == "LO") selected @endif @else @if(old('type') == "LO")
                    selected @endif @endif>LO</option>
                <option value="LP" @if ($unit) @if($unit->type == "LP") selected @endif @else @if(old('type') == "LP")
                    selected @endif @endif>LP</option>
                <option value="HELI" @if ($unit) @if($unit->type == "HELI") selected @endif @else @if(old('type') ==
                    "HELI") selected @endif @endif>HELI</option>
            </select>
        </div>
        <div class="form-group">
            <label>Matricula</label>
            <input type="text" class="form-control" placeholder="Matricula" name="plate" @if ($unit)
                value="{{$unit->plate}}" @else value="{{old('type')}}" @endif>
        </div>
        <div class="form-group">
            <label>Nº de Cauda</label>
            <input type="text" class="form-control" placeholder="Nº de Cauda" name="tail_number" @if ($unit)
                value="{{$unit->tail_number}}" @else value="{{old('tail_number')}}" @endif>
        </div>
        <div class="form-group">
            <label>Observações</label>
            <textarea class="form-control" placeholder="Observações" name="observations"
                rows="3">@if ($unit){{$unit->observations}} @else {{old('observations')}} @endif</textarea>
        </div>
        <div class="form-group">
            <label>Estrutura</label>
            <input type="text" class="form-control" placeholder="Estrutura" name="structure" @if ($unit)
                value="{{$unit->structure}}" @else {{old('structure')}} @endif>
        </div>
        <div class="form-group">
            <label>Latitude da Base</label>
            <input type="text" class="form-control" placeholder="Latitude da Base" name="base_lat" @if ($unit)
                value="{{$unit->base_lat}}" @else {{old('base_lat')}} @endif>
        </div>
        <div class="form-group">
            <label>Longitude da Base</label>
            <input type="text" class="form-control" placeholder="Longitude da Base" name="base_long" @if ($unit)
                value="{{$unit->base_long}}" @else {{old('base_long')}} @endif>
        </div>
        <button type="submit" class="btn btn-secondary">{{$unit?"Guardar":"Criar"}}</button>
        @if ($theater_of_operations)
        @if($unit)
        <a class="btn btn-info"
            href="{{route('theaters_of_operations.units.single',["id"=>$theater_of_operations->id,"unit_id" => $unit->id])}}">Voltar</a>
        @else
        <a class="btn btn-info" href="{{route('theaters_of_operations.single',$theater_of_operations->id)}}">Voltar</a>
        @endif
        @else
        @endif
    </form>
</div>
@endsection