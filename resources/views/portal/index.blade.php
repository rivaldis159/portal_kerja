<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Kerja BPS Kabupaten Dairi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <div class="flex items-center gap-3">
                <img src="/images/logo.png" alt="Logo" class="h-9 w-auto">
                <div class="flex flex-col">
                    <span class="text-lg font-bold text-gray-900 leading-none tracking-tight">Portal Kerja</span>
                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">BPS Kabupaten Dairi</span>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-5">

                {{-- Cek apakah user memiliki role admin --}}
                @if(in_array(Auth::user()->role, ['super_admin', 'admin_tim']))
                    <a href="/admin" class="hidden sm:flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Manajemen Portal
                    </a>
                @endif
                
                <a href="{{ route('portal.profile') }}" class="flex items-center gap-3 group hover:bg-gray-50 p-1.5 pr-3 rounded-full transition-all border border-transparent hover:border-gray-200">
                    <div class="hidden md:block text-right mr-1">
                        <div class="text-xs font-bold text-gray-700 group-hover:text-blue-600 transition">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-gray-400">Edit Profil</div>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold shadow-md ring-2 ring-white group-hover:ring-blue-100 transition">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </a>

                <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center h-9 w-9 sm:w-auto sm:px-3 sm:py-1.5 sm:space-x-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Keluar Aplikasi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="hidden sm:inline text-sm font-medium">Keluar</span>
                    </button>
                </form>

            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

        <div class="text-center space-y-6 max-w-2xl mx-auto pt-4">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                Mau ngerjain apa hari ini?
            </h1>
            <p class="text-gray-500 text-sm md:text-base">
                Temukan aplikasi, dokumen, dan alat kerja BPS Dairi dengan cepat.
            </p>

            <div class="relative w-full" x-data="searchBox()">
                <div class="relative group shadow-lg rounded-2xl">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input
                        type="text"
                        x-model="query"
                        @input="search()"
                        @focus="showDropdown = true"
                        @click.away="showDropdown = false"
                        placeholder="Ketik kata kunci pencarian..."
                        class="w-full pl-12 pr-4 py-4 bg-white border-0 ring-1 ring-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none text-lg transition-shadow placeholder-gray-400"
                        autocomplete="off"
                    >
                </div>

                <div
                    x-show="showDropdown && (results.length > 0 || (query.length > 0 && !loading))"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute top-full left-0 right-0 mt-3 bg-white rounded-xl shadow-xl border border-gray-100 max-h-96 overflow-y-auto z-40 divide-y divide-gray-50 text-left"
                    style="display: none;">

                    <div x-show="loading" class="p-4 text-center text-gray-500 text-sm">Mencari...</div>

                    <template x-for="link in results" :key="link.id">
                        <a :href="'/link/' + link.id" target="_blank" class="flex items-start p-4 hover:bg-blue-50 transition-colors group">
                            <div class="flex-shrink-0 mr-4">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold shadow-sm text-sm"
                                     :style="'background-color: ' + (link.color || '#3B82F6')">
                                     <span x-text="link.title.substring(0,1).toUpperCase()"></span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-blue-700" x-text="link.title"></h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-gray-500" x-text="link.team_name"></span>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full" x-text="link.category_name || 'Umum'"></span>
                                </div>
                            </div>
                        </a>
                    </template>

                    <div x-show="!loading && results.length === 0 && query.length > 0" class="p-6 text-center text-gray-500">
                        Tidak ditemukan hasil untuk "<span x-text="query" class="font-bold"></span>"
                    </div>
                </div>
            </div>
        </div>

        @if($announcements->count() > 0)
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-1 text-white overflow-hidden relative">
            <div class="bg-white/10 p-4 sm:p-6 rounded-xl backdrop-blur-sm">
                <h3 class="flex items-center gap-2 font-bold text-lg mb-3">
                    <svg class="w-5 h-5 text-yellow-300 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    Papan Pengumuman
                </h3>
                <div class="space-y-2">
                    @foreach($announcements as $announcement)
                        <div class="flex items-start gap-3 text-sm sm:text-base border-b border-white/10 pb-2 last:border-0 last:pb-0">
                            <span class="bg-white/20 px-2 py-0.5 rounded text-xs font-mono mt-0.5">{{ $announcement->created_at->format('d/m') }}</span>
                            <p class="leading-relaxed opacity-95">{{ $announcement->content }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="space-y-6 pb-20">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">Daftar Tim & Layanan</h2>
                <button @click="expandAll = !expandAll" x-data="{ expandAll: false }" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    </button>
            </div>

            @foreach ($teams as $team)
                <div x-data="{ expanded: false }" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-md">
                    
                    <button 
                        @click="expanded = !expanded" 
                        class="w-full flex items-center justify-between p-5 bg-white hover:bg-gray-50 transition-colors cursor-pointer text-left focus:outline-none">
                        
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center text-white shadow-sm shrink-0 transition-transform duration-300"
                                 :class="expanded ? 'scale-110' : ''"
                                 style="background-color: {{ $team->color ?? '#3B82F6' }}">
                                <span class="font-bold text-lg">{{ strtoupper(substr($team->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900">{{ $team->name }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $team->links->count() }} tautan tersedia</p>
                            </div>
                        </div>

                        <div class="h-8 w-8 rounded-full flex items-center justify-center bg-gray-100 text-gray-400 transition-transform duration-300"
                             :class="expanded ? 'rotate-180 bg-blue-100 text-blue-600' : ''">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </button>

                    <div x-show="expanded" x-collapse x-cloak class="border-t border-gray-100 bg-gray-50/50">
                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            
                            @php
                                $groupedLinks = $team->links->groupBy(function($item) {
                                    return $item->category ? $item->category->name : 'Lainnya';
                                });
                            @endphp

                            @foreach ($groupedLinks as $categoryName => $links)
                                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm h-full flex flex-col">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 border-b border-gray-100 pb-2">
                                        {{ $categoryName }}
                                    </h4>
                                    
                                    <div class="space-y-2 flex-1">
                                        @foreach ($links as $link)
                                            <a href="{{ route('link.redirect', $link->id) }}" target="_blank" class="flex items-center gap-3 p-2 rounded-lg hover:bg-blue-50 group transition-colors">
                                                <div class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-blue-500 transition-colors"></div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-700 group-hover:text-blue-700 leading-tight">
                                                        {{ $link->title }}
                                                    </div>
                                                    @if($link->description)
                                                        <div class="text-[10px] text-gray-400 line-clamp-1 mt-0.5">{{ $link->description }}</div>
                                                    @endif
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            @if($team->links->isEmpty())
                                <div class="col-span-full text-center py-4 text-gray-400 text-sm italic">
                                    Belum ada link untuk tim ini.
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <footer class="text-center pt-10 pb-6 border-t border-gray-200">
            <p class="text-gray-400 text-sm">
                &copy; {{ date('Y') }} BPS Kabupaten Dairi. Dibuat dengan ❤️ oleh Tim IPDS.
            </p>
        </footer>

    </main>

    <script>
    function searchBox() {
        return {
            query: '',
            results: [],
            showDropdown: false,
            loading: false,
            searchTimeout: null,

            search() {
                clearTimeout(this.searchTimeout);
                if (this.query.length < 2) {
                    this.results = [];
                    return;
                }
                this.loading = true;
                this.searchTimeout = setTimeout(() => {
                    fetch(`/api/search-links?q=${encodeURIComponent(this.query)}`)
                        .then(response => response.json())
                        .then(data => {
                            this.results = data.slice(0, 10); 
                            this.loading = false;
                        })
                        .catch(() => {
                            this.loading = false;
                        });
                }, 300);
            }
        }
    }
    </script>
</body>
</html>