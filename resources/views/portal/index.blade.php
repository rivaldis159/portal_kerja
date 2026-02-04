<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Kerja BPS Kabupaten Dairi</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { bg-transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fffbf7', 100: '#fef3e6', 200: '#fce3c7', 300: '#facd9f',
                            400: '#f8b170', 500: '#f79039', 600: '#e87221', 700: '#c15518',
                            800: '#99431a', 900: '#7c3818',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="flex h-full overflow-hidden bg-slate-50" x-data="{ sidebarOpen: false, search: '' }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-slate-900/80 z-40 lg:hidden" x-cloak></div>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 transform transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                <div>
                    <h1 class="font-bold text-slate-800 text-base leading-none">Portal Kerja</h1>
                    <span class="text-[10px] text-slate-500 font-medium tracking-wide uppercase">BPS Kabupaten Dairi</span>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
            <p class="px-2 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Kategori</p>

            @foreach($categories as $category)
                @if($category->links->isNotEmpty())
                <a href="#cat-{{ $category->id }}" @click="sidebarOpen = false"
                   class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition group">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300 group-hover:bg-blue-500 transition"></span>
                    {{ $category->name }}
                </a>
                @endif
            @endforeach
        </div>

        <!-- Footer Sidebar -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            <p class="text-[10px] text-center text-slate-400 font-medium">&copy; {{ date('Y') }} BPS Kabupaten Dairi</p>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
        
        <!-- Top Header -->
        <header class="bg-white border-b border-slate-200 h-16 shrink-0 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-30 relative">
            <div class="flex items-center gap-4 flex-1">
                <!-- Mobile Toggle -->
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700 p-2 -ml-2 rounded-lg hover:bg-slate-100 transition">
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

            <div class="flex items-center gap-4 lg:hidden">
                 <!-- Mobile Search Trigger -->
                 <button class="text-slate-500 p-2" @click="search = ''">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                 </button>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3 ml-4">
                @auth
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-3 hover:bg-slate-50 p-1.5 pr-3 rounded-full transition border border-transparent hover:border-slate-200">
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
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full group flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                                    <svg class="mr-3 h-4 w-4 text-red-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="px-5 py-2 bg-primary-600 text-white rounded-lg font-medium text-sm hover:bg-primary-700 transition shadow-sm hover:shadow-md">
                   Login
                </a>
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
                        @auth
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-2">
                            Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-orange-600">{{ Auth::user()->name }}</span> 👋
                        </h2>
                        <p class="text-slate-500">Selamat datang! Akses semua aplikasi dan link kerja BPS Kabupaten Dairi di sini</p>
                        @else
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-2">Selamat Datang 👋</h2>
                        <p class="text-slate-500">Silakan login untuk mengakses fitur lengkap Portal Kerja.</p>
                        @endauth
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="space-y-10 min-h-[50vh]">
                    @forelse($categories as $category)
                        @if($category->links->isNotEmpty())
                        <section id="cat-{{ $category->id }}" class="category-section scroll-mt-24" 
                            x-data="{ hasVisibleLinks: true, expanded: true }" 
                            x-show="hasVisibleLinks" 
                            x-effect="hasVisibleLinks = search === '' || [...$el.querySelectorAll('a[data-title]')].some(el => el.dataset.title.includes(search.toLowerCase()) || el.dataset.description.includes(search.toLowerCase()))"
                            x-transition>
                            
                            <div class="flex items-center gap-4 mb-5 cursor-pointer select-none group/cat" @click="expanded = !expanded">
                                <button class="bg-slate-100 hover:bg-slate-200 p-1.5 rounded-lg text-slate-500 transition">
                                    <svg class="w-4 h-4 transform transition-transform duration-200" :class="{'rotate-180': !expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <h3 class="text-lg font-bold text-slate-800 uppercase tracking-wide group-hover/cat:text-primary-600 transition">{{ $category->name }}</h3>
                                <div class="h-px bg-slate-200 flex-1 group-hover/cat:bg-primary-100 transition"></div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6"
                                 x-show="expanded" x-collapse>
                                @foreach($category->links as $link)
                                <a href="{{ route('link.redirect', $link) }}"
                                   target="{{ $link->target ?? '_blank' }}"
                                   class="group relative bg-white rounded-xl border border-slate-200 p-4 hover:border-blue-400 hover:shadow-lg hover:shadow-blue-500/10 transition-all duration-300 hover:-translate-y-1 flex flex-col h-full overflow-hidden"
                                   data-title="{{ strtolower($link->title) }}"
                                   data-description="{{ strtolower($link->description ?? '') }}"
                                   x-show="search === '' || $el.dataset.title.includes(search.toLowerCase()) || $el.dataset.description.includes(search.toLowerCase())">
                                    
                                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $link->is_vpn_required ? 'bg-red-400' : 'bg-blue-500' }}"></div>

                                    <div class="flex justify-between items-start mb-2 pl-2">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            @if($link->is_bps_pusat)
                                            <span class="text-[10px] font-bold bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded border border-slate-200">PUSAT</span>
                                            @elseif($link->team)
                                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded border border-current/10" style="color: {{ $link->team->color }}; background-color: {{ $link->team->color }}10;">
                                                {{ Str::limit($link->team->name, 12) }}
                                            </span>
                                            @endif
                                        </div>
                                        @if($link->is_vpn_required)
                                        <span class="text-[10px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded border border-red-100 flex items-center gap-1">
                                            <span class="w-1 h-1 rounded-full bg-red-500 animate-pulse"></span> VPN
                                        </span>
                                        @endif
                                    </div>

                                    <h4 class="font-bold text-slate-800 group-hover:text-blue-600 transition pl-2 line-clamp-2 mb-1">{{ $link->title }}</h4>
                                    
                                    @if($link->description)
                                    <p class="text-xs text-slate-500 pl-2 line-clamp-2 mb-3">{{ $link->description }}</p>
                                    @endif

                                    <div class="mt-auto pl-2 flex items-center gap-2 pt-2 border-t border-slate-50">
                                        <div class="bg-slate-50 p-1 rounded text-slate-400 group-hover:text-blue-500 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-mono truncate max-w-[150px]">{{ parse_url($link->url, PHP_URL_HOST) ?? 'External Link' }}</span>
                                    </div>
                                </a>
                                @endforeach
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
                    <div x-show="search.length > 0 && document.querySelectorAll('.category-section a[x-show]:not([style*=\'display: none\'])').length === 0"
                        class="flex flex-col items-center justify-center py-20 text-center" style="display: none;">
                        <h4 class="text-lg font-bold text-slate-900 mb-1">Tidak ditemukan</h4>
                        <p class="text-slate-500 mb-4">Tidak ada hasil untuk "<span x-text="search" class="font-semibold text-slate-800"></span>"</p>
                        <button @click="search = ''" class="text-primary-600 hover:text-primary-800 text-sm font-semibold hover:underline">Reset Pencarian</button>
                    </div>
                </div>
            </div>
            
        </main>
    </div>
</body>
</html>