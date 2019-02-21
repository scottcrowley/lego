<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @yield('head')
</head>
<body class="bg-primary-lightest h-screen antialiased">
    <div id="app" v-cloak>
        <nav class="bg-white h-13 shadow mb-8 px-6 md:px-0">
            <div class="container mx-auto h-full flex flex-col content-around">
                <div class="flex items-center justify-center pt-3">
                    <div class="flex-1 md:flex-none text-center md:text-left ml-8 md:ml-0">
                        <a href="{{ url('/') }}" class="text-lg font-hairline text-primary-darker no-underline hover:underline">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>
                    <div class="md:flex-1 md:text-right">
                        @guest
                            <a class="no-underline hover:underline text-primary-darker pr-3 text-sm" href="{{ url('/login') }}">{{ __('Login') }}</a>
                            <a class="no-underline hover:underline text-primary-darker text-sm" href="{{ url('/register') }}">{{ __('Register') }}</a>
                        @else
                            <dropdown>
                                <div slot="link" class="block md:hidden">
                                    <button class="burger" style="outline: none;">
                                        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                                    </button>
                                </div>
                                <button slot="link" class="hidden md:block btn is-primary is-small" style="outline: none;">{{ Auth::user()->name }}</button>
        
                                <div slot="dropdown-items" class="text-right pr-3 pl-10">
                                    <a href="{{ route('dashboard') }}">Dashboard</a>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                </div>
                            </dropdown>
                        @endguest
                    </div>
                </div>
                <div class="flex item-end justify-center mb-2 mt-2 md:mt-3">
                    @if (auth()->check())
                        <dropdown>
                            <a slot="link" class="dropdown-toggle" href="#">Storage</a>
                            <div slot="dropdown-items" class="pl-3 pr-10">
                                <a href="{{ route('storage.types.index') }}">Types</a>
                                <a href="{{ route('storage.locations.index') }}">Locations</a>
                            </div>
                        </dropdown>
                    @endif
                </div>
            </div>
        </nav>

        @yield('content')

        <flash message="{{ session('flash.message') }}" baselevel="{{ session('flash.level') }}"></flash>
    </div>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
