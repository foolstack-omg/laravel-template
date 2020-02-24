<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel ') - Laravel </title>
    <!-- 样式 -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    {{--@include('layouts._header')--}}
    <div class="container">
        @yield('content')
    </div>
    {{--@include('layouts._footer')--}}
</div>
<!-- JS 脚本 -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('scriptsAfterJs')
</body>
</html>
