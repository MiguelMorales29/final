<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => Auth::user()?->theme === 'dark']) x-data="{ sidebarOpen: false }" @toggle-sidebar.window="sidebarOpen = !sidebarOpen">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Smart Study Hub</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/sshlogo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/sshlogo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 overflow-x-hidden">
    <div class="flex min-h-screen overflow-x-hidden">
        <!-- Sidebar -->
        <aside class="hidden lg:block fixed left-0 top-0 h-screen w-64 bg-white dark:bg-gray-900 shadow-md z-40">
            <x-student-sidebar />
        </aside>
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
             @click="sidebarOpen = false">
        </div>
        
        <!-- Mobile Sidebar -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 shadow-lg lg:hidden">
            <x-student-sidebar />
        </div>
        
        <!-- Main Content Area -->
        <main class="flex-1 p-6 bg-gray-50 dark:bg-gray-900 lg:ml-64 min-w-0 overflow-x-hidden">
            <!-- Top Navigation -->
            <x-student-top-nav />
            
            <!-- Page Content -->
            <div class="mt-6 min-w-0 overflow-x-hidden">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
                <button @click="show = false" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
                <button @click="show = false" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Glass Blur Scroll Effect -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const nav = document.getElementById('topbar');
            if (!nav) return;
            
            const onScroll = () => {
                if (window.scrollY > 6) {
                    nav.classList.add('backdrop-blur', 'bg-white/70', 'dark:bg-gray-900/60', 'shadow');
                    nav.classList.remove('bg-white', 'dark:bg-gray-800');
                } else {
                    nav.classList.remove('backdrop-blur', 'bg-white/70', 'dark:bg-gray-900/60', 'shadow');
                    nav.classList.add('bg-white', 'dark:bg-gray-800');
                }
            };
            
            onScroll(); // initial state
            window.addEventListener('scroll', onScroll, { passive: true });
        });
    </script>

    <!-- Smooth Scrolling for Anchor Links -->
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</body>
</html>










