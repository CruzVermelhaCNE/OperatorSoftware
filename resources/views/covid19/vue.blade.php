<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link href="{{ mix('css/dashboard.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>COVID 19 - Operadores</title>
</head>

<body class="text-white">
    <div id="app">
        <div class="container-fluid">
            <div class="row" style="height: 100%">
                <Sidebar v-bind:sections="[
                    {
                        header:null,items: [
                            {name:'Inicio',  route: '/', icon:'home'},
                        ]
                    },
                    {
                        header:null,items: [
                            {name:'Sair', route:null, icon:'arrow-left', bottom:true, external_route:'{{ route('salop.index') }}'},
                        ]
                    },
                ]"></Sidebar>
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10">
                    <keep-alive>
                        <router-view v-if="$route.meta.keepAlive"></router-view>
                    </keep-alive>
                    <router-view v-if="!$route.meta.keepAlive"></router-view>
                </main>
            </div>
        </div>
    </div>
    <script src="{{ mix('js/covid19/app.js') }}"></script>
</body>

</html>