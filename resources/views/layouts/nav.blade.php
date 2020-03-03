<nav class="navbar bg-white shadow-lg mb-8">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <div class="flex items-center flex-1">
            <!-- Left Side Of Navbar -->
            <div class="subnav collapsable w-full">
                @include('layouts.subnav')
            </div>

            <!-- Right Side Of Navbar -->
            <div class="ml-auto mr-4 flex-none">
                <!-- Authentication Links -->
                @guest
                    <div class="pl-0 mb-0 hidden md:block">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    </div>
                    <dropdown align="right" width="200px" class="block md:hidden">
                        <div slot="trigger">
                            <button class="burger" style="outline: none;">
                                <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                            </button>
                        </div>

                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    </dropdown>
                @else
                    <dropdown align="right" width="200px" data-cy="main-menu">
                        <div slot="trigger">
                            <div class="block md:hidden">
                                <button class="burger" style="outline: none;">
                                    <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                                </button>
                            </div>
                            <div class="hidden md:block">
                                <button class="dropdown-toggle-link block flex items-center text-secondary-800 no-underline text-base focus:outline-none" v-pre>
                                    {{ auth()->user()->name }}
                                </button>
                            </div>
                        </div>

                        <div class="subnav">
                            @include('layouts.subnav')
                        </div>
                        <a href="{{ route('dashboard') }}" class="dropdown-menu-link w-full text-left">Dashboard</a>
                        <a href="#" 
                            class="dropdown-menu-link w-full text-left"
                            data-cy="logout-button"
                            onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">  
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </dropdown>
                @endguest
            </div>
        </div>
    </div>
</nav>