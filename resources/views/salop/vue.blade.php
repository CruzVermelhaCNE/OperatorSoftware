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
    <title>Painel de Operadores</title>
</head>

<body class="text-white">
    <div id="app">
        <div class="container-fluid">
            <div class="row" style="height: 100%">
                <Sidebar v-bind:sections="[
                    {
                        header:null,items: [
                            {name:'Inicio',  route: '/', icon:'home'},
                            @can('accessSALOP')
                            {name:'Sistema de Telefones', route: '/phones', icon:'sliders'},
                            {name:'Video Porteiro', route: '/door', icon:'video'},
                            @endcan
                        ]
                    },
                    @can('accessGOI')
                    {
                        header:'Gestão Operacional Integrada', items: [
                            {name:'Abrir', route:null, icon:'map', external_route:'{{ route('goi.index') }}'},
                        ]
                    },
                    @endcan
                    @can('isManager')
                    {
                        header:'Gestão', items: [
                            {name:'Utilizadores', route:'/users', icon:'user'},
                            {name:'Relatórios', route:'/reports', icon:'bar-chart-2'},
                        ]
                    },
                    @endcan
                    @can('isAdmin')
                    {
                        header:'Administração',items: [
                            {name:'Extensões', route:'/extensions', icon:'phone'},
                        ]
                    },
                    @endcan
                    {
                        header:null,items: [
                            {name:'Sair', route:null, icon:'power', bottom:true, external_route:'{{ route('auth.logout') }}'},
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
    <script src="{{ mix('js/salop/app.js') }}"></script>
</body>

</html>