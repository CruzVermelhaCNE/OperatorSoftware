@extends('theaters_of_operations/layouts/panel')

@if ($coordination)
@section('pageTitle', 'Editar Coordenador')
@else
@section('pageTitle', 'Criar Coordenador')
@endif

@section('style')
@parent
@endsection

@section('content')
<div class="container">
    <h2>Coordenador</h2>
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
        action="{{$coordination?route('theaters_of_operations.coordination.edit',["id"=>$theater_of_operations->id,"coordination_id"=>$coordination->id]):route('theaters_of_operations.coordination.create',$theater_of_operations->id)}}">
        @else
        @endif
        @csrf
        @if ($coordination)
        <input type="hidden" name="id" value="{{$coordination->id}}">

        @else
        @if ($theater_of_operations)
        <input type="hidden" name="theater_of_operations_id" value="{{$theater_of_operations->id}}">
        @else
        <input type="hidden" name="theater_of_operations_sector_id" value="{{$theater_of_operations_sector->id}}">
        @endif
        @endif
        <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" placeholder="Nome" name="name" @if ($coordination)
                value="{{$coordination->name}}" @endif>
        </div>
        <div class="form-group">
            <label>Cargo</label>
            <select class="form-control" name="role">
                <option value="Oficial de Ligação" @if ($coordination) @if($coordination->role == "Oficial de Ligação")
                    selected @endif @endif>Oficial de Ligação</option>
                <option value="Coordenador" @if ($coordination) @if($coordination->role == "Coordenador") selected
                    @endif @endif>Coordenador</option>
            </select>
        </div>
        <div class="form-group">
            <label>Contacto</label>
            <input type="text" class="form-control" placeholder="Contacto" name="contact" @if ($coordination)
                value="{{$coordination->contact}}" @endif>
        </div>
        <div class="form-group">
            <label>Observações</label>
            <textarea class="form-control" placeholder="Observações" name="observations"
                rows="3">@if ($coordination){{$coordination->observations}}@endif</textarea>
        </div>
        <button type="submit" class="btn btn-secondary">{{$coordination?"Guardar":"Criar"}}</button>
        @if ($theater_of_operations)
        <a class="btn btn-info" href="{{route('theaters_of_operations.single',$theater_of_operations->id)}}">Voltar</a>
        @if($coordination)
        <a class="btn btn-danger"
            href="{{route('theaters_of_operations.coordination.remove',["id"=>$theater_of_operations->id,"coordination_id"=>$coordination->id])}}">Apagar</a>
        @endif
        @else
        @endif
    </form>
</div>
@endsection

@section('javascript')
@parent
@endsection