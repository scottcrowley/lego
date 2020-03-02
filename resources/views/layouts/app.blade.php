<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', config('app.name', 'Laravel')) - Lego Project
    </title>

    <!-- Fonts -->

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @yield('head')
</head>
<body>
    <div id="app" v-cloak>
        @include('layouts.nav')

        <div class="container flex justify-center pb-24">
            @yield('content')
        </div>

        <flash message="{{ session('flash.message') }}" baselevel="{{ session('flash.level') }}"></flash>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
