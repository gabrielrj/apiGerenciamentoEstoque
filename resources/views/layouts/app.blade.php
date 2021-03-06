<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- FAV ICON -->
    {{--<link rel='shortcut icon' type='image/x-icon' href="{{asset('img/logo_gsi_credi_80x80.png')}}" />--}}

    <title>{{ env('APP_TITLE') }}</title>

    <!-- Styles e Fonts -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous"/>
    <link href="{{ asset('css/materialize.css') }}" type="text/css" media="screen,projection" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" type="text/css" media="screen,projection" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" type="text/css" media="screen,projection" rel="stylesheet">
</head>
<body>
@section('menu')
    @include('layouts.menu')
@show

<main>
    <div id="app" class="content">
        {{--@include('layouts.alerts')--}}
        @yield('content')
    </div>
</main>

<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>

@include('layouts.footer')

</body>


</html>
