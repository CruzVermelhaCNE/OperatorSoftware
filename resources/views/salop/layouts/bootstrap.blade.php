<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @yield('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('pageTitle') - Painel de Operadores</title>
  </head>
  <body>
    @yield('body')    
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('javascript')
    </body>
</html>