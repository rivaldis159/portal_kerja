<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SIPETRIK - Portal Kerja' }}</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="flex h-full overflow-hidden bg-slate-50 dark:bg-gray-900" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
         class="fixed inset-0 bg-slate-900/80 z-40 lg:hidden" x-cloak></div>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 border-r border-slate-200 dark:border-gray-700 transform transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-slate-100 dark:border-gray-700 shrink-0">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                <div>
                    <h1 class="font-bold text-slate-800 dark:text-white text-base leading-none">SIPETRIK</h1>
                    <span class="text-[10px] text-slate-500 dark:text-gray-400 font-medium tracking-wide uppercase">BPS Dairi</span>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
            <a href="{{ route('sipetrik.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('sipetrik.dashboard') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400' : 'text-slate-600 hover:bg-slate-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            
            <p class="px-2 mt-6 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Manajemen</p>
            
            <a href="{{ route('sipetrik.mitras.index') }}" 
               class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('sipetrik.mitras.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400' : 'text-slate-600 hover:bg-slate-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Data Mitra
            </a>
            
            <a href="{{ route('sipetrik.penawaran-kerja.index') }}" 
               class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('sipetrik.penawaran-kerja.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400' : 'text-slate-600 hover:bg-slate-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Penawaran Kerja
            </a>
            
            <a href="{{ route('sipetrik.penilaian.index') }}" 
               class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('sipetrik.penilaian.*') ? 'bg-orange-50 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400' : 'text-slate-600 hover:bg-slate-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                Penilaian Kinerja
            </a>

            <div class="border-t border-slate-100 dark:border-gray-700 my-4 pt-4">
                 <a href="/" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-slate-500 hover:text-slate-800 dark:text-gray-400 dark:hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Portal
                </a>
            </div>
        </div>

        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-slate-100 dark:border-gray-700 bg-slate-50/50 dark:bg-gray-800">
            <p class="text-[10px] text-center text-slate-400 font-medium">&copy; {{ date('Y') }} BPS Kabupaten Dairi</p>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
        
        <!-- Top Header -->
        <header class="bg-white dark:bg-gray-800 border-b border-slate-200 dark:border-gray-700 h-16 shrink-0 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-30 relative">
            <div class="flex items-center gap-4 flex-1">
                <!-- Mobile Toggle -->
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700 dark:text-gray-400 dark:hover:text-white p-2 -ml-2 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-700 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3 ml-4">
                @auth
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-gray-700 p-1.5 pr-3 rounded-full transition border border-transparent hover:border-slate-200 dark:hover:border-gray-600">
                        <div class="h-8 w-8 rounded-full bg-orange-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="text-left hidden md:block">
                            <p class="text-xs font-bold text-slate-700 dark:text-white leading-tight">{{ auth()->user()->name }}</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 hidden md:block" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-slate-100 dark:border-gray-700 py-1 origin-top-right z-50">
                        <div class="px-4 py-3 border-b border-slate-50 dark:border-gray-700">
                             <p class="text-xs text-slate-500 dark:text-gray-400">Login sebagai</p>
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="p-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full group flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
                                    <svg class="mr-3 h-4 w-4 text-red-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-slate-50/50 dark:bg-gray-900 p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/30 p-4 border border-green-100 dark:border-green-800 flex gap-3 items-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm text-green-700 dark:text-green-300 font-medium">{{ session('success') }}</p>
                </div>
                @endif
                
                @if(session('error'))
                <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-900/30 p-4 border border-red-100 dark:border-red-800 flex gap-3 items-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <p class="text-sm text-red-700 dark:text-red-300 font-medium">{{ session('error') }}</p>
                </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>