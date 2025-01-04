<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <meta name="csrf-token" content="{{ csrf_token() }}">
=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
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
<<<<<<< HEAD
=======
                                    <a href="{{ route('chat.index') }}" 
                                       class="inline-flex items-center px-1 text-white hover:text-gray-200 {{ request()->routeIs('chat.*') ? 'border-b-2 border-white' : '' }}">
                                        Messages
                                    </a>
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
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
<<<<<<< HEAD
                            @if(Auth::user()->email === 'adminb@gmail.com')
                                <a href="{{ route('chat.index') }}" 
                                   class="text-sm font-medium text-white hover:text-gray-200 relative mr-4">
                                    Messages
                                    @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                            {{ $unreadMessagesCount }}
                                        </span>
                                    @endif
                                </a>
                            @else
                                <a href="{{ route('chat.contact-admin') }}" 
                                   class="text-sm font-medium text-white hover:text-gray-200">
                                    Contact Us
                                </a>
                            @endif
                            <a href="{{ route('profile.index') }}" 
                               class="text-sm font-medium text-white hover:text-gray-200 relative">
                                {{ Auth::user()->username }}
                                @if(isset($reportedProductsCount) && $reportedProductsCount > 0)
                                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                        {{ $reportedProductsCount }}
                                    </span>
                                @endif
=======
                            <a href="{{ route('profile.index') }}" 
                               class="text-sm font-medium text-white hover:text-gray-200">
                                {{ Auth::user()->username }}
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
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
<<<<<<< HEAD
=======
                    <a href="{{ route('chat.index') }}" 
                       class="block pl-3 pr-4 py-2 text-white hover:text-gray-200 hover:bg-custom-navy {{ request()->routeIs('chat.*') ? 'bg-custom-navy' : '' }}">
                        Messages
                    </a>
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
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
<<<<<<< HEAD
        @auth
            @php
                $reportNotification = Session::get('report_notification_' . Auth::id());
            @endphp
            
            @if($reportNotification)
                <div class="fixed bottom-4 right-4 max-w-sm w-full bg-white rounded-lg shadow-lg border-l-4 border-blue-500 p-4" 
                     x-data="{ show: true }" 
                     x-show="show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-full"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-full">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm leading-5 font-medium text-gray-900">
                                New Admin Feedback
                            </p>
                            <p class="mt-1 text-sm leading-5 text-gray-500">
                                {{ $reportNotification['message'] }}
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('profile.index', ['tab' => 'reports']) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-blue-200 transition ease-in-out duration-150">
                                    View Details
                                </a>
                            </div>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false" class="inline-flex text-gray-400 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        @endauth
=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
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
