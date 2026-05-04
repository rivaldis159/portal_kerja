<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Kerja BPS Kabupaten Dairi</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="flex h-full overflow-hidden bg-slate-50" x-data="{ sidebarOpen: window.innerWidth >= 1024, search: '', activeTab: 'Sistem' }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-slate-900/80 z-40 lg:hidden" x-cloak></div>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 bg-white border-slate-200 transition-all duration-300 lg:static flex flex-col overflow-hidden shrink-0"
           :class="sidebarOpen ? 'translate-x-0 w-72 border-r' : '-translate-x-full w-72 lg:w-0 lg:border-r-0 lg:translate-x-0'">
        
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-slate-100 shrink-0">
            <a href="{{ route('portal.index') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                <div>
                    <h1 class="font-bold text-slate-800 text-base leading-none group-hover:text-orange-600 transition">Portal Kerja</h1>
                    <span class="text-[10px] text-slate-500 font-medium tracking-wide uppercase">BPS Kabupaten Dairi</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Navigasi</p>

            @foreach($categories as $category)
                @php
                    $hasLinks = $category->subcategories->flatMap->links->isNotEmpty() || $category->links->isNotEmpty();
                @endphp
                @if($hasLinks)
                <div x-data="{ open: false }" class="mb-1">
                    <!-- Category Header -->
                    <button @click="open = !open" 
                            class="flex items-center gap-2.5 w-full px-3 py-2 text-sm font-semibold rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition group cursor-pointer">
                        <!-- Category Icon -->
                        <span class="w-7 h-7 rounded-lg bg-slate-100 group-hover:bg-orange-50 flex items-center justify-center text-slate-400 group-hover:text-orange-500 transition shrink-0">
                            @switch($category->icon)
                                @case('clipboard-document-list')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    @break
                                @case('chart-bar')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    @break
                                @case('beaker')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    @break
                                @case('signal')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.348 14.651a3.75 3.75 0 010-5.303m5.304 0a3.75 3.75 0 010 5.303m-7.425 2.122a6.75 6.75 0 010-9.546m9.546 0a6.75 6.75 0 010 9.546M5.106 18.894c-3.808-3.808-3.808-9.98 0-13.789m13.788 0c3.808 3.808 3.808 9.981 0 13.79M12 12h.008v.007H12V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"></path></svg>
                                    @break
                                @case('calendar-days')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"></path></svg>
                                    @break
                                @case('banknotes')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"></path></svg>
                                    @break
                                @case('user-group')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path></svg>
                                    @break
                                @case('scale')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 01-2.031.352 5.989 5.989 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971z"></path></svg>
                                    @break
                                @case('building-office')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"></path></svg>
                                    @break
                                @case('computer-desktop')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25z"></path></svg>
                                    @break
                                @case('shield-check')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"></path></svg>
                                    @break
                                @case('academic-cap')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"></path></svg>
                                    @break
                                @default
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"></path></svg>
                            @endswitch
                        </span>
                        <span class="truncate flex-1 text-left">{{ $category->name }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 shrink-0" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>

                    <!-- Subcategories -->
                    <div x-show="open" x-collapse class="ml-5 mt-0.5 space-y-0.5 border-l-2 border-slate-100 pl-3">
                        @foreach($category->subcategories as $sub)
                            @if($sub->links->isNotEmpty())
                            <a href="#sub-{{ $sub->id }}" @click="if(window.innerWidth < 1024) sidebarOpen = false"
                               class="flex items-center gap-2 px-2 py-1.5 text-xs font-medium rounded-md text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition">
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                {{ $sub->name }}
                                <span class="ml-auto text-[10px] text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded">{{ $sub->links->count() }}</span>
                            </a>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        <!-- Footer Sidebar -->
        <div class="p-3 border-t border-slate-100 bg-slate-50/50">
            <a href="{{ route('landing') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"></path></svg>
                PODA (Publik)
            </a>
            <form method="POST" action="{{ route('logout') }}" x-data x-ref="logoutSidebar">
                @csrf
                <button type="button" @click="if(confirm('Apakah Anda yakin akan keluar?')) $refs.logoutSidebar.submit()" class="flex items-center gap-3 w-full px-3 py-2.5 text-sm font-medium text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition cursor-pointer group">
                    <svg class="w-5 h-5 text-red-400 group-hover:text-red-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
        
        <!-- Top Header -->
        <header class="bg-white border-b border-slate-200 h-16 shrink-0 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-30 relative">
            <div class="flex items-center gap-4 flex-1">
                <!-- Toggle -->
                <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-slate-700 p-2 -ml-2 rounded-lg hover:bg-slate-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

            <!-- Centered Search Bar -->
            <div class="hidden lg:block absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-xl px-4">
                <div class="relative w-full group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" x-model="search" placeholder="Cari aplikasi atau link (Ctrl+K)..." 
                           class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg leading-5 bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm transition-all shadow-sm"
                           @keydown.window.ctrl.k.prevent="$el.focus()">
                     <button x-show="search.length > 0" @click="search = ''" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer" style="display: none;">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3 ml-4">
                @auth
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-3 hover:bg-slate-50 p-1.5 pr-3 rounded-full transition border border-transparent hover:border-slate-200 cursor-pointer">
                        <div class="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold text-sm shadow-sm ring-2 ring-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="text-left hidden md:block">
                            <p class="text-xs font-bold text-slate-700 leading-tight">{{ auth()->user()->name }}</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 hidden md:block" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-slate-100 py-1 origin-top-right z-50 focus:outline-none ring-1 ring-black/5">
                        <div class="px-4 py-3 border-b border-slate-50">
                            <p class="text-xs text-slate-500">Login sebagai</p>
                            <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="p-1">
                            <a href="{{ route('portal.profile') }}" class="group flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg">
                                <svg class="mr-3 h-4 w-4 text-slate-400 group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Edit Profil
                            </a>
                            <a href="{{ route('portal.stats') }}" class="group flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-green-50 hover:text-green-600 rounded-lg">
                                <svg class="mr-3 h-4 w-4 text-slate-400 group-hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Dashboard Pegawai
                            </a>
                            @if(auth()->user()->canAccessPanel(Filament\Facades\Filament::getPanel('admin')))
                            <a href="/admin" class="group flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-600 rounded-lg">
                                <svg class="mr-3 h-4 w-4 text-slate-400 group-hover:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Manajemen Portal
                            </a>
                            @endif
                        </div>
                        <div class="border-t border-slate-50 my-1"></div>
                        <div class="p-1">
                            <form method="POST" action="{{ route('logout') }}" x-data x-ref="logoutDropdown">
                                @csrf
                                <button type="button" @click="if(confirm('Apakah Anda yakin akan keluar?')) $refs.logoutDropdown.submit()" class="w-full group flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg cursor-pointer">
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

        <!-- Main Content Scrollable Area -->
        <main class="flex-1 overflow-y-auto bg-slate-50/50 p-4 sm:p-6 lg:p-8 scroll-smooth" id="main-content">
            
            <div class="max-w-7xl mx-auto space-y-12 pb-20">
                
                <!-- Welcome Banner -->
                <div class="relative bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-primary-50 to-transparent"></div>
                    <div class="relative z-10 max-w-2xl">
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-2">
                            Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-orange-600">{{ Auth::user()->name }}</span>!
                        </h2>
                        <p class="text-slate-500">Akses semua aplikasi dan link kerja BPS Kabupaten Dairi di sini</p>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="flex gap-4 border-b border-slate-200 mb-8">
                    <button @click="activeTab = 'Sistem'" :class="activeTab === 'Sistem' ? 'border-primary-500 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="px-4 py-3 border-b-2 font-bold text-sm transition focus:outline-none">Sistem/Aplikasi</button>
                    <button @click="activeTab = 'Arsip'" :class="activeTab === 'Arsip' ? 'border-primary-500 text-primary-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="px-4 py-3 border-b-2 font-bold text-sm transition focus:outline-none">Arsip/Dokumentasi</button>
                </div>

                <!-- Content Grid -->
                <div class="space-y-10 min-h-[50vh]">
                    
                    @forelse($categories as $category)
                        @php
                            $allLinks = $category->subcategories->flatMap->links->merge($category->links ?? collect());
                            $hasVisibleLinks = $allLinks->isNotEmpty();
                        @endphp
                        @if($hasVisibleLinks)
                        <section id="cat-{{ $category->id }}" class="category-section scroll-mt-24" 
                            x-data="{ expanded: true }" 
                            x-show="[...($el.querySelectorAll('a[data-type]') || [])].some(el => el.dataset.type === activeTab && (search === '' || el.dataset.title.includes(search.toLowerCase()) || (el.dataset.description || '').includes(search.toLowerCase())))"
                            x-transition>
                            
                            <!-- Category Header -->
                            <div class="flex items-center gap-3 mb-5 cursor-pointer select-none group/cat" @click="expanded = !expanded">
                                <button class="bg-slate-100 hover:bg-slate-200 p-1.5 rounded-lg text-slate-500 transition">
                                    <svg class="w-4 h-4 transform transition-transform duration-200" :class="{'rotate-180': !expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <h3 class="text-lg font-bold text-slate-800 uppercase tracking-wide group-hover/cat:text-primary-600 transition">{{ $category->name }}</h3>
                                <div class="h-px bg-slate-200 flex-1 group-hover/cat:bg-primary-100 transition"></div>
                            </div>

                            <div x-show="expanded" x-collapse>
                                <!-- Subcategories -->
                                @foreach($category->subcategories as $subcategory)
                                    @if($subcategory->links->isNotEmpty())
                                    <div id="sub-{{ $subcategory->id }}" class="mb-6 scroll-mt-24"
                                         x-show="[...($el.querySelectorAll('a[data-type]') || [])].some(el => el.dataset.type === activeTab && (search === '' || el.dataset.title.includes(search.toLowerCase()) || (el.dataset.description || '').includes(search.toLowerCase())))">
                                        
                                        @if($subcategory->name !== 'Umum' || $category->subcategories->count() > 1)
                                        <h4 class="text-sm font-semibold text-slate-500 mb-3 pl-1 flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary-400"></span>
                                            {{ $subcategory->name }}
                                        </h4>
                                        @endif

                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
                                            @foreach($subcategory->links as $link)
                                            <a href="{{ route('link.redirect', $link) }}"
                                               target="{{ $link->target ?? '_blank' }}"
                                               class="group relative bg-white rounded-xl border border-slate-200 p-3 hover:border-blue-400 hover:shadow-lg hover:shadow-blue-500/10 transition-all duration-300 hover:-translate-y-1 flex flex-col min-h-fit min-w-0 overflow-hidden"
                                               data-title="{{ strtolower($link->title) }}"
                                               data-description="{{ strtolower($link->description ?? '') }}"
                                               data-type="{{ $link->type ?? 'Sistem' }}"
                                               x-show="(search === '' || $el.dataset.title.includes(search.toLowerCase()) || $el.dataset.description.includes(search.toLowerCase())) && $el.dataset.type === activeTab">
                                                
                                                <div class="absolute left-0 top-0 bottom-0 w-1 {{ $link->is_vpn_required ? 'bg-red-400' : 'bg-blue-500' }}"></div>

                                                <div class="flex justify-between items-start mb-1.5 pl-2">
                                                    <div class="flex items-center gap-1.5 flex-wrap">
                                                        @if($link->is_bps_pusat)
                                                        <span class="text-[9px] font-bold bg-slate-100 text-slate-600 px-1 py-0.5 rounded border border-slate-200 uppercase">PUSAT</span>
                                                        @elseif($link->team)
                                                        <span class="text-[9px] font-bold px-1 py-0.5 rounded border border-current/10" style="color: {{ $link->team->color }}; background-color: {{ $link->team->color }}10;">
                                                            {{ Str::limit($link->team->name, 12) }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                    @if($link->is_vpn_required)
                                                    <span class="text-[9px] font-bold text-red-600 bg-red-50 px-1 py-0.5 rounded border border-red-100 flex items-center gap-1">
                                                        <span class="w-1 h-1 rounded-full bg-red-500 animate-pulse"></span> VPN
                                                    </span>
                                                    @endif
                                                </div>

                                                <h4 class="font-bold text-sm text-slate-800 group-hover:text-blue-600 transition pl-2 line-clamp-2 mb-1 leading-snug">{{ $link->title }}</h4>
                                                
                                                @if($link->description)
                                                <p class="text-[11px] text-slate-500 pl-2 line-clamp-2 mb-2 leading-relaxed">{{ $link->description }}</p>
                                                @endif

                                                <div class="mt-auto pl-2 flex items-center gap-1.5 pt-2 border-t border-slate-50">
                                                    <div class="bg-slate-50 p-0.5 rounded text-slate-400 group-hover:text-blue-500 transition">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                    </div>
                                                    <span class="text-[10px] text-slate-400 font-mono truncate max-w-[150px]">{{ parse_url($link->url, PHP_URL_HOST) ?? 'External Link' }}</span>
                                                </div>
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                @endforeach

                                <!-- Uncategorized links (no subcategory) -->
                                @if($category->links->isNotEmpty())
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 mb-6">
                                    @foreach($category->links as $link)
                                    <a href="{{ route('link.redirect', $link) }}"
                                       target="{{ $link->target ?? '_blank' }}"
                                       class="group relative bg-white rounded-xl border border-slate-200 p-3 hover:border-blue-400 hover:shadow-lg hover:shadow-blue-500/10 transition-all duration-300 hover:-translate-y-1 flex flex-col min-h-fit min-w-0 overflow-hidden"
                                       data-title="{{ strtolower($link->title) }}"
                                       data-description="{{ strtolower($link->description ?? '') }}"
                                       data-type="{{ $link->type ?? 'Sistem' }}"
                                       x-show="(search === '' || $el.dataset.title.includes(search.toLowerCase()) || $el.dataset.description.includes(search.toLowerCase())) && $el.dataset.type === activeTab">
                                        
                                        <div class="absolute left-0 top-0 bottom-0 w-1 {{ $link->is_vpn_required ? 'bg-red-400' : 'bg-blue-500' }}"></div>
                                        <div class="flex justify-between items-start mb-1.5 pl-2">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                @if($link->is_bps_pusat)
                                                <span class="text-[9px] font-bold bg-slate-100 text-slate-600 px-1 py-0.5 rounded border border-slate-200 uppercase">PUSAT</span>
                                                @elseif($link->team)
                                                <span class="text-[9px] font-bold px-1 py-0.5 rounded border border-current/10" style="color: {{ $link->team->color }}; background-color: {{ $link->team->color }}10;">
                                                    {{ Str::limit($link->team->name, 12) }}
                                                </span>
                                                @endif
                                            </div>
                                            @if($link->is_vpn_required)
                                            <span class="text-[9px] font-bold text-red-600 bg-red-50 px-1 py-0.5 rounded border border-red-100 flex items-center gap-1">
                                                <span class="w-1 h-1 rounded-full bg-red-500 animate-pulse"></span> VPN
                                            </span>
                                            @endif
                                        </div>
                                        <h4 class="font-bold text-sm text-slate-800 group-hover:text-blue-600 transition pl-2 line-clamp-2 mb-1 leading-snug">{{ $link->title }}</h4>
                                        @if($link->description)
                                        <p class="text-[11px] text-slate-500 pl-2 line-clamp-2 mb-2 leading-relaxed">{{ $link->description }}</p>
                                        @endif
                                        <div class="mt-auto pl-2 flex items-center gap-1.5 pt-2 border-t border-slate-50">
                                            <div class="bg-slate-50 p-0.5 rounded text-slate-400 group-hover:text-blue-500 transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </div>
                                            <span class="text-[10px] text-slate-400 font-mono truncate max-w-[150px]">{{ parse_url($link->url, PHP_URL_HOST) ?? 'External Link' }}</span>
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </section>
                        @endif
                    @empty
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Belum ada link</h3>
                        <p class="text-slate-500 text-sm">Hubungi admin untuk menambahkan aplikasi.</p>
                    </div>
                    @endforelse

                    <!-- Empty State for Search -->
                    <div x-show="search.length > 0 && document.querySelectorAll('.category-section:not([style*=\'display: none\'])').length === 0"
                        class="flex flex-col items-center justify-center py-20 text-center" x-cloak>
                        <h4 class="text-lg font-bold text-slate-900 mb-1">Tidak ditemukan</h4>
                        <p class="text-slate-500 mb-4">Tidak ada hasil pencarian untuk "<span x-text="search" class="font-semibold text-slate-800"></span>"</p>
                        <button @click="search = ''" class="text-primary-600 hover:text-primary-800 text-sm font-semibold hover:underline">Reset Pencarian</button>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</body>
</html>