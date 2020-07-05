@extends('theaters_of_operations/layouts/panel')

@if ($crew)
@section('pageTitle', 'Editar Operacional')
@else
@section('pageTitle', 'Criar Operacional')
@endif

@section('style')
@parent
@endsection

@section('content')
<div class="container">
    <h2>Operacional</h2>
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
        action="{{$crew?route('theaters_of_operations.crews.edit',["id"=>$theater_of_operations->id,"crew_id"=>$crew->id]):route('theaters_of_operations.crews.create',$theater_of_operations->id)}}">
        @else
        @endif
        @csrf
        @if ($crew)
        <input type="hidden" name="id" value="{{$crew->id}}">
        @else
        @if ($theater_of_operations)
        <input type="hidden" name="theater_of_operations_id"
            value="{{($theater_of_operations)?$theater_of_operations->id:$theater_of_operations_sector->theater_of_operations_id}}">
        @endif
        @endif
        <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" placeholder="Nome" name="name" @if ($crew) value="{{$crew->name}}"
                @else value="{{old('name')}}" @endif>
        </div>
        <div class="form-group">
            <label>Contacto</label>
            <input type="text" class="form-control" placeholder="Contacto" name="contact" @if ($crew)
                value="{{$crew->contact}}" @else value="{{old('contact')}}" @endif>
        </div>
        <div class="form-group">
            <label>Idade</label>
            <input type="text" class="form-control" placeholder="Idade" name="age" @if ($crew) value="{{$crew->age}}"
                @else value="{{old('age')}}" @endif>
        </div>
        <div class="form-group">
            <label>Formação</label>
            <select class="form-control" name="course">
                <option value="TAT" @if ($crew) @if($crew->course == "TAT") selected @endif @else @if(old('course') == "TAT") selected @endif @endif>TAT</option>
                <option value="TAS" @if ($crew) @if($crew->course == "TAS") selected @endif @else @if(old('course') == "TAS") selected @endif @endif>TAS</option>
                <option value="Enfermeiro" @if ($crew) @if($crew->course == "Enfermeiro") selected @endif @else @if(old('course') == "Enfermeiro") selected @endif
                    @endif>Enfermeiro</option>
                <option value="Médico" @if ($crew) @if($crew->course == "Médico") selected @endif @else @if(old('course') == "Médico") selected @endif @endif>Médico</option>
                <option value="Psicólogo" @if ($crew) @if($crew->course == "Psicólogo") selected @endif @else @if(old('course') == "Psicólogo") selected @endif @endif>Psicólogo
                </option>
            </select>
        </div>
        <div class="form-group">
            <label>Observações</label>
            <textarea class="form-control" placeholder="Observações" name="observations"
                rows="3">@if ($crew){{$crew->observations}} @else {{old('observations')}} @endif</textarea>
        </div>
        <button type="submit" class="btn btn-secondary">{{$crew?"Guardar":"Criar"}}</button>
        @if ($theater_of_operations)

        @if($crew)
        <a class="btn btn-info"
            href="{{route('theaters_of_operations.crews.single',["id"=>$theater_of_operations->id,"crew_id" => $crew->id])}}">Voltar</a>

        @else
        <a class="btn btn-info" href="{{route('theaters_of_operations.single',$theater_of_operations->id)}}">Voltar</a>
        @endif
        @else
        @endif
    </form>
</div>
@endsection