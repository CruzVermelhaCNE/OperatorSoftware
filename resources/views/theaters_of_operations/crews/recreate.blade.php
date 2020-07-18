@extends('theaters_of_operations/layouts/panel')


@section('pageTitle', 'Readicionar Operacional')


@section('style')
@parent
@endsection

@section('content')
<div class="container">
    <h2>Readicionar Operacional</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="POST" action="{{route('theaters_of_operations.crews.recreate',$theater_of_operations->id)}}">
        @csrf
        <input type="hidden" name="theater_of_operations_id" value="{{$theater_of_operations->id}}">
        <div class="form-group">
            <label for="crew_selector">Operacional</label>
            <select class="form-control" id="crew_selector" name="crew">
                @foreach ($deleted_crews as $crew)
                    <option value='{{$crew->id}}'>{{$crew->name}}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-secondary">Readicionar</button>
        <a class="btn btn-info" href="{{route('theaters_of_operations.single',$theater_of_operations->id)}}">Voltar</a>
    </form>
</div>
@endsection

@section('javascript')
@parent
<script type="text/javascript">
    $('#crew_selector').select2({
        theme: 'bootstrap4',
    });
</script>
@endsection