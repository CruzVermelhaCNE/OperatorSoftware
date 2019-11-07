@extends('layouts/panel')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 panel">
    <div class="form-group col-md-3" id="extension_picker">
        <h3>Selecione a sua Extensão</h3>
        <select class="form-control">
            <option></option>
            @foreach (Auth::user()->extensions as $extension)
            <option data-password="{{ $extension->extension->password }}">{{ $extension->extension->number }}</option>
            @endforeach
        </select>
    </div>
    <iframe src="" style="display:none;"></iframe>
</main>
@endsection

@section('javascript')
@parent
<script>
    $("#extension_picker select").change(function () {
        let number = $(this).val();
        let password = $(this).find(":selected").data("password");
        $("iframe").attr("src","{{ env('FOP2_ADDRESS') }}?exten="+number+"&pass="+password);
        $("#extension_picker").hide();
        $("iframe").show();
    })
</script>
@endsection