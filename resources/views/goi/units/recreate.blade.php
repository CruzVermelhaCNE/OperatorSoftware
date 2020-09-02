@extends('goi/layouts/panel')


@section('pageTitle', 'Readicionar Meio')


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
    <form method="POST" action="{{route('goi.units.recreate',$theater_of_operations->id)}}">
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
        <button type="submit" class="btn btn-secondary">Readicionar</button>
        <a class="btn btn-info" href="{{route('goi.single',$theater_of_operations->id)}}">Voltar</a>
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