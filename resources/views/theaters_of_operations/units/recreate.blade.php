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
    <h2>Readicionar Meio</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="POST" action="{{route('theaters_of_operations.units.recreate',$theater_of_operations->id)}}">
        @csrf
        <input type="hidden" name="theater_of_operations_id" value="{{$theater_of_operations->id}}">
        <div class="form-group">
            <label for="unit_selector">Meio</label>
            <select class="form-control" id="unit_selector" name="unit">
                @foreach ($deleted_units as $unit)
                    <option value='{{$unit->id}}'>{{$unit->tail_number}} {{$unit->plate}}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>
@endsection

@section('javascript')
@parent
<script type="text/javascript">
    $('#unit_selector').select2({
        theme: 'bootstrap4',
    });
</script>
@endsection