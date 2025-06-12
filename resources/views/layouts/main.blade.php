<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rental Blacklist') }} - @yield('title', 'Sistem Blacklist Rental')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">
                                <i class="fas fa-shield-alt text-red-600 mr-2"></i>
                                {{ $globalSettings['site_name'] ?? 'RentalGuard' }}
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('home') ? 'border-red-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                <i class="fas fa-search mr-2"></i>
                                Cari Blacklist
                            </a>

                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard*') ? 'border-red-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                    Dashboard
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @auth
                            <div class="relative">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150" id="user-menu-button">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>

                                <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden" id="user-menu">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="space-x-4">
                                <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium">
                                    <i class="fas fa-sign-in-alt mr-1"></i>
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                    <i class="fas fa-user-plus mr-1"></i>
                                    Register
                                </a>
                            </div>
                        @endauth
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" id="mobile-menu-button">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div class="sm:hidden hidden" id="mobile-menu">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('home') ? 'border-red-500 text-red-700 bg-red-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out">
                        <i class="fas fa-search mr-2"></i>
                        Cari Blacklist
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard*') ? 'border-red-500 text-red-700 bg-red-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                    @endauth
                </div>

                @auth
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition duration-150 ease-in-out">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition duration-150 ease-in-out">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="space-y-1">
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition duration-150 ease-in-out">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition duration-150 ease-in-out">
                                <i class="fas fa-user-plus mr-2"></i>
                                Register
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-6">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        $('#mobile-menu-button').click(function() {
            $('#mobile-menu').toggleClass('hidden');
        });

        // User menu toggle
        $('#user-menu-button').click(function() {
            $('#user-menu').toggleClass('hidden');
        });

        // Close dropdowns when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('#user-menu-button, #user-menu').length) {
                $('#user-menu').addClass('hidden');
            }
        });

        // CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
