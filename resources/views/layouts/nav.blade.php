{{-- <nav class="navbar bg-white shadow">
    <div class="mx-auto h-full flex flex-col">
        <div class="flex items-center pt-3">
            <div class="text-center sm:text-left md:ml-0">
                <a href="{{ url('/') }}" class="text-lg font-hairline text-primary-700 no-underline hover:underline">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
            <div class="ml-auto">
                @guest
                    <a class="block sm:inline sm:pr-3 sm:mb-2 no-underline hover:underline text-primary-700 text-sm" href="{{ url('/login') }}">{{ __('Login') }}</a>
                    <a class="block sm:inline no-underline hover:underline text-primary-700 text-sm" href="{{ url('/register') }}">{{ __('Register') }}</a>
                @else
                    <div data-cy="main-menu">
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
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    data-cy="logout-button">{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </div>
                        </dropdown>
                    </div>
                @endguest
            </div>
        </div>
        <div class="flex item-end justify-center mb-2 mt-2 md:mt-3">
            @if (auth()->check())
                <dropdown>
                    <a href="#"
                        slot="link" 
                        class="dropdown-toggle toggle-closed" 
                        data-cy="menu-your-legos"
                    >Your Legos</a>
                    <div slot="dropdown-items">
                        <a href="{{ route('legouser.sets.grid') }}">Sets</a>
                        <a href="{{ route('legouser.parts.all') }}">Parts - All</a>
                        <a href="{{ route('legouser.parts.individual') }}">Parts - Individual</a>
                    </div>
                </dropdown>
                <dropdown>
                    <a href="#" 
                        slot="link" 
                        class="dropdown-toggle toggle-closed"
                        data-cy="menu-lego"
                    >Lego</a>
                    <div slot="dropdown-items">
                        <a href="{{ route('lego.sets.grid') }}">Sets</a>
                        <a href="{{ route('lego.themes') }}">Themes</a>
                        <a href="{{ route('lego.parts.grid') }}">Parts</a>
                        <a href="{{ route('lego.part_categories') }}">Part Categories</a>
                        <a href="{{ route('lego.colors') }}">Colors</a>
                        <a href="{{ route('lego.inventories.grid') }}">Set Inventories</a>
                    </div>
                </dropdown>
                <dropdown>
                    <a href="#" 
                        slot="link" 
                        class="dropdown-toggle toggle-closed"
                        data-cy="menu-storage"
                    >Storage</a>
                    <div slot="dropdown-items">
                        <a href="{{ route('storage.types.index') }}">Types</a>
                        <a href="{{ route('storage.locations.index') }}">Locations</a>
                        <a href="{{ route('storage.locations.parts.unassigned') }}">Unassigned Parts</a>
                    </div>
                </dropdown>
            @endif
        </div>
    </div>
</nav> --}}

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
                        <a href="#" class="dropdown-menu-link w-full text-left"
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