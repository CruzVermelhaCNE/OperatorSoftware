@extends('layouts/panel')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 panel">
    <div class="container">
        <a href="#" class="btn btn-primary">Abrir Porta</a>
        <div class="embed-responsive embed-responsive-16by9" id="door_opener_video">
            
        </div>
    </div>
</main>
@endsection

@section('javascript')
@parent
<script type="text/javascript">

$(document).ready(() => {
    $.ajax(type:"GET",crossdomain: true,url:"https://{{ env('GDS3710_IP')}}/jpeg/stream?type=0&user={{ env('GDS3710_USERNAME')}}", success:
    function(data){
        let parser = new DOMParser();
        let xmlDoc = parser.parseFromString(data,"text/xml");
        let ChallengeCode = xmlDoc.getElementsByTagName("ChallengeCode")[0].nodeValue;
        let IDCode = xmlDoc.getElementsByTagName("IDCode")[0].nodeValue;
        $.get("/data/gds_auth_code?cc="+ChallengeCode, function(AuthCode, status) {
            $('#door_opener_video').append("https://{{ env('GDS3710_IP')}}/jpeg/stream?type=1&user={{ env('GDS3710_USERNAME')}}&authcode="+AuthCode+"&idcode="+IDCode);
        });
    });
});
</script>
@endsection