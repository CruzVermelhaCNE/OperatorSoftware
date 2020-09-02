@extends('goi/layouts/panel')

@if ($communication_channel)
@section('pageTitle', 'Editar Canal de Comunicações')
@else
@section('pageTitle', 'Criar Canal de Comunicações')
@endif

@section('style')
@parent
@endsection

@section('content')
<div class="container">
    <h2>Canal de Comunicações</h2>
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
        action="{{$communication_channel?route('goi.communication_channels.edit',["id"=>$theater_of_operations->id,"communication_channel_id"=>$communication_channel->id]):route('goi.communication_channels.create',$theater_of_operations->id)}}">
        @else
        @endif
        @csrf
        @if ($communication_channel)
        <input type="hidden" name="id" value="{{$communication_channel->id}}">

        @else
        @if ($theater_of_operations)
        <input type="hidden" name="theater_of_operations_id" value="{{$theater_of_operations->id}}">
        @else
        <input type="hidden" name="theater_of_operations_sector_id" value="{{$theater_of_operations_sector->id}}">
        @endif
        @endif
        <div class="form-group">
            <label>Tipo</label>
            <select class="form-control" name="type">
                <option value="SIRESP" @if ($communication_channel) @if($communication_channel->type == "SIRESP")
                    selected @endif @else @if (old("type") == "SIRESP") selected @endif @endif>SIRESP</option>
                <option value="VHF" @if ($communication_channel) @if($communication_channel->type == "VHF") selected
                    @endif @else @if (old("type") == "VHF") selected @endif @endif>VHF</option>
                <option value="UHF" @if ($communication_channel) @if($communication_channel->type == "UHF") selected
                    @endif @else @if (old("type") == "UHF") selected @endif @endif>UHF</option>
            </select>
        </div>
        <div class="form-group">
            <label>Canal</label>
            <input type="text" class="form-control" placeholder="Canal" name="channel" @if ($communication_channel)
                value="{{$communication_channel->channel}}" @else value="{{old('channel')}}" @endif>
        </div>
        <div class="form-group">
            <label>Observações</label>
            <textarea class="form-control" placeholder="Observações" name="observations"
                rows="3">@if ($communication_channel){{$communication_channel->observations}}@else {{old('observations')}}@endif</textarea>
        </div>
        <button type="submit" class="btn btn-secondary">{{$communication_channel?"Guardar":"Criar"}}</button>
        @if ($theater_of_operations)
        <a class="btn btn-info" href="{{route('goi.single',$theater_of_operations->id)}}">Voltar</a>
        @if($communication_channel)
        <a class="btn btn-danger"
            href="{{route('goi.communication_channels.remove',["id"=>$theater_of_operations->id,"communication_channel_id"=>$communication_channel->id])}}">Apagar</a>
        @endif
        @else
        @endif
    </form>
</div>
@endsection

@section('javascript')
@parent
@endsection