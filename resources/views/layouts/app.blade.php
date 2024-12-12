<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetEase</title>
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Styles -->
    <style>
        [x-cloak] { display: none !important; }
        .bg-custom-navy {
            background-color: #2d3e50;
        }
        .hover-custom-navy:hover {
            background-color: #3d526b;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-custom-navy shadow-lg" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center -mt-3">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/Logo.png') }}" alt="BudgetEase Logo" class="h-24 w-auto">
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden sm:flex flex-1 items-center justify-center">
                        @auth
                            <div class="absolute left-1/2 transform -translate-x-1/2 bg-white bg-opacity-30 rounded-lg px-6 py-2">
                                <div class="flex space-x-8">
                                    <a href="{{ route('home') }}" 
                                       class="inline-flex items-center px-1 text-white hover:text-gray-200 {{ request()->routeIs('home') ? 'border-b-2 border-white' : '' }}">
                                        Home
                                    </a>
                                    <a href="{{ route('budgets.index') }}" 
                                       class="inline-flex items-center px-1 text-white hover:text-gray-200 {{ request()->routeIs('budgets.*') ? 'border-b-2 border-white' : '' }}">
                                        Budget Tracking
                                    </a>
                                    <a href="{{ route('marketplace.index') }}" 
                                       class="inline-flex items-center px-1 text-white hover:text-gray-200 {{ request()->routeIs('marketplace.*') ? 'border-b-2 border-white' : '' }}">
                                        Marketplace
                                    </a>
                                    <a href="{{ route('chat.index') }}" 
                                       class="inline-flex items-center px-1 text-white hover:text-gray-200 {{ request()->routeIs('chat.*') ? 'border-b-2 border-white' : '' }}">
                                        Messages
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Authentication Links -->
                <div class="hidden sm:flex sm:items-center sm:ml-auto">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm text-white hover:text-gray-200">Login</a>
                        <a href="{{ route('register') }}" class="ml-4 text-sm text-white hover:text-gray-200">Register</a>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('profile.index') }}" 
                               class="text-sm font-medium text-white hover:text-gray-200">
                                {{ Auth::user()->username }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                                @csrf
                                <button type="submit" class="text-sm text-white hover:text-gray-200">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @endguest
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-200 hover:bg-custom-navy focus:outline-none focus:bg-custom-navy focus:text-gray-200 transition" aria-label="Main menu" aria-expanded="false" @click="open = !open">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="sm:hidden" id="mobile-menu" x-show="open">
            <div class="pt-2 pb-3 space-y-1">
                @auth
                    <a href="{{ route('home') }}" 
                       class="block pl-3 pr-4 py-2 text-white hover:text-gray-200 hover:bg-custom-navy {{ request()->routeIs('home') ? 'bg-custom-navy' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('budgets.index') }}" 
                       class="block pl-3 pr-4 py-2 text-white hover:text-gray-200 hover:bg-custom-navy {{ request()->routeIs('budgets.*') ? 'bg-custom-navy' : '' }}">
                        Budget Tracking
                    </a>
                    <a href="{{ route('marketplace.index') }}" 
                       class="block pl-3 pr-4 py-2 text-white hover:text-gray-200 hover:bg-custom-navy {{ request()->routeIs('marketplace.*') ? 'bg-custom-navy' : '' }}">
                        Marketplace
                    </a>
                    <a href="{{ route('chat.index') }}" 
                       class="block pl-3 pr-4 py-2 text-white hover:text-gray-200 hover:bg-custom-navy {{ request()->routeIs('chat.*') ? 'bg-custom-navy' : '' }}">
                        Messages
                    </a>
                    <div class="pt-4 pb-3 border-t border-gray-600">
                        <div class="px-4">
                            <a href="{{ route('profile.index') }}" class="block text-base font-medium text-white hover:text-gray-200">
                                {{ Auth::user()->username }}
                            </a>
                        </div>
                        <div class="mt-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-white hover:text-gray-200 hover:bg-custom-navy">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 text-white hover:text-gray-200 hover:bg-custom-navy">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 text-white hover:text-gray-200 hover:bg-custom-navy">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        // document.querySelector('button[aria-label="Main menu"]').addEventListener('click', function() {
        //     const mobileMenu = document.getElementById('mobile-menu');
        //     const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
        //     this.setAttribute('aria-expanded', !isExpanded);
        //     mobileMenu.classList.toggle('hidden');
        // });
    </script>
</body>
</html>
