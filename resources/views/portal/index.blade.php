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
    </style>
</head>

<body class="h-full text-slate-800 bg-slate-50/50 relative selection:bg-blue-100 selection:text-blue-700" x-data="{ search: '' }">

    <!-- Ambient Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-[10%] -right-[10%] w-[600px] h-[600px] rounded-full bg-blue-100/40 blur-[80px] mix-blend-multiply"></div>
        <div class="absolute top-[20%] -left-[10%] w-[500px] h-[500px] rounded-full bg-indigo-100/40 blur-[80px] mix-blend-multiply"></div>
        <div class="absolute -bottom-[20%] right-[20%] w-[500px] h-[500px] rounded-full bg-cyan-100/40 blur-[80px] mix-blend-multiply"></div>
    </div>

    <nav class="bg-white/70 backdrop-blur-xl sticky top-0 z-50 border-b border-slate-200/60 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-white p-1.5 rounded-lg shadow-sm border border-slate-100">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo BPS" class="h-9 w-auto">
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-slate-800 text-lg leading-tight tracking-tight">Portal Kerja</span>
                    <span class="text-[10px] font-bold text-blue-600 tracking-widest uppercase bg-blue-50 px-2 py-0.5 rounded-md w-fit">BPS Kabupaten Dairi</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                @auth
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-3 hover:bg-slate-100/80 p-1.5 pr-3 rounded-full transition group border border-transparent hover:border-slate-200">
                        <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-md ring-2 ring-white group-hover:ring-blue-100 transition">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-bold text-slate-700 group-hover:text-blue-700 transition">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide group-hover:text-slate-500 transition">{{ auth()->user()->employeeDetail->jabatan ?? 'Pegawai' }}</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" x-cloak class="absolute right-0 mt-3 w-64 bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-slate-100 py-2 origin-top-right z-50 focus:outline-none">
                        <div class="px-5 py-3 border-b border-slate-50">
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Akun Saya</p>
                            <p class="text-sm font-medium text-slate-900 truncate mt-1">{{ auth()->user()->email }}</p>
                        </div>
                        
                        <div class="p-1.5">
                            <a href="{{ route('portal.profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-700 rounded-xl transition group">
                                <span class="bg-blue-100 text-blue-600 p-1.5 rounded-lg group-hover:bg-blue-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </span>
                                Edit Profil & Biodata
                            </a>
    
                            <a href="{{ route('portal.stats') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-green-50 hover:text-green-700 rounded-xl transition group">
                                <span class="bg-green-100 text-green-600 p-1.5 rounded-lg group-hover:bg-green-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                </span>
                                Dashboard Pegawai
                            </a>
    
                            @if(auth()->user()->canAccessPanel(Filament\Facades\Filament::getPanel('admin')))
                            <a href="/admin" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-purple-50 hover:text-purple-700 rounded-xl transition group">
                                <span class="bg-purple-100 text-purple-600 p-1.5 rounded-lg group-hover:bg-purple-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </span>
                                Manajemen Portal
                            </a>
                            @endif
                        </div>

                        <div class="border-t border-slate-100 my-1 mx-2"></div>
                        
                        <div class="p-1.5">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 rounded-xl transition group">
                                    <span class="bg-red-50 text-red-500 p-1.5 rounded-lg group-hover:bg-red-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    </span>
                                    Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="group relative px-6 py-2.5 bg-blue-600 text-white rounded-xl font-medium shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all overflow-hidden">
                   <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-shimmer"></div>
                   <span>Login Pegawai</span>
                </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10">

        <!-- Announcement Bannner -->
        @if(isset($announcements) && $announcements->count() > 0)
        <div class="mb-12" x-show="search === ''" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl shadow-xl shadow-orange-500/20 p-6 text-white relative overflow-hidden group hover:shadow-2xl hover:shadow-orange-500/30 transition-all border border-orange-400/50">
                <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                
                <div class="flex items-start gap-5 relative z-10">
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-md shrink-0 ring-1 ring-white/30 shadow-inner">
                        <svg class="w-6 h-6 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                            Pengumuman Penting
                            <span class="text-[10px] bg-white/25 px-2 py-0.5 rounded-full font-bold uppercase tracking-wider backdrop-blur-sm">Terbaru</span>
                        </h3>
                        <div class="space-y-4">
                            @foreach($announcements as $announcement)
                            <div class="relative pl-4 border-l-2 border-white/30 hover:border-white transition-colors">
                                <p class="font-bold text-sm text-white mb-1 drop-shadow-sm">{{ $announcement->title }}</p>
                                <p class="text-xs text-white/90 leading-relaxed max-w-3xl">{{ Str::limit($announcement->content, 150) }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Hero & Search -->
        <div class="mb-12 flex flex-col md:flex-row justify-between items-end gap-8 border-b border-slate-200/60 pb-8">
            <div class="w-full md:w-auto">
                @auth
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">Halo, <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ explode(' ', auth()->user()->name)[0] }}!</span></h1>
                    <span class="text-3xl animate-wave">👋</span>
                </div>
                <p class="text-slate-500 text-sm font-medium">Akses cepat aplikasi dan link pendukung kinerja.</p>
                @else
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">Selamat Datang</h1>
                     <span class="text-3xl animate-wave">👋</span>
                </div>
                <p class="text-slate-500 text-sm font-medium">Silakan login untuk akses fitur lengkap.</p>
                @endauth
            </div>

            <div class="w-full md:w-96 relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500 text-slate-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input
                    type="text"
                    x-model="search"
                    placeholder="Cari aplikasi atau link..."
                    class="block w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-2xl leading-5 bg-white/80 backdrop-blur-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 sm:text-sm shadow-sm transition-all hover:bg-white"
                >
                <button
                    x-show="search.length > 0"
                    @click="search = ''"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer transition"
                    style="display: none;">
                    <svg class="h-4 w-4 bg-slate-200 rounded-full p-0.5 hover:bg-slate-300 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <!-- Links Grid -->
        <div class="space-y-16 min-h-[50vh]">
            @forelse($categories as $category)
            @if($category->links->isNotEmpty())
            <div x-data="{ hasVisibleLinks: true }" x-show="hasVisibleLinks" class="category-section" x-transition>
                <div class="flex items-center gap-4 mb-6">
                    <div class="bg-white p-2 rounded-xl shadow-sm border border-slate-100 text-blue-600">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">{{ $category->name }}</h2>
                    <div class="h-px bg-slate-200 flex-1 ml-4 shadow-sm"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($category->links as $link)
                    <a
                        href="{{ $link->url }}"
                        target="{{ $link->target ?? '_blank' }}"
                        class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm hover:shadow-xl hover:shadow-blue-500/10 border border-slate-200/60 p-5 transition-all duration-300 hover:-translate-y-1.5 flex flex-col h-full relative overflow-hidden"
                        data-title="{{ strtolower($link->title) }}"
                        data-description="{{ strtolower($link->description ?? '') }}"
                        x-show="search === '' || $el.dataset.title.includes(search.toLowerCase()) || $el.dataset.description.includes(search.toLowerCase())">

                        <!-- Hover Gradient -->
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-indigo-50/50 opacity-0 group-hover:opacity-100 transition duration-500"></div>
                        <div class="absolute left-0 top-0 bottom-0 w-1 {{ $link->is_vpn_required ? 'bg-red-400' : 'bg-blue-500' }} rounded-l-2xl"></div>

                        <div class="relative z-10 flex flex-col h-full">
                            <div class="flex justify-between items-start mb-3 pl-2">
                                <div class="flex items-center gap-2">
                                    @if($link->is_bps_pusat)
                                    <span class="text-[10px] font-bold bg-slate-100 text-slate-600 px-2 py-1 rounded-md uppercase tracking-wider border border-slate-200">PUSAT</span>
                                    @elseif($link->team)
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-md uppercase tracking-wider border border-current/10" style="color: {{ $link->team->color }}; background-color: {{ $link->team->color }}10;">
                                        {{ Str::limit($link->team->name, 15) }}
                                    </span>
                                    @endif
                                </div>
    
                                @if($link->is_vpn_required)
                                <div class="flex items-center gap-1.5 text-[10px] font-bold bg-red-50 text-red-600 px-2 py-1 rounded-md border border-red-100 shadow-sm">
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></div>
                                    VPN
                                </div>
                                @endif
                            </div>
    
                            <h3 class="text-base font-bold text-slate-800 group-hover:text-blue-700 transition pl-2 line-clamp-2 leading-snug mb-1">
                                {{ $link->title }}
                            </h3>
                            
                            @if($link->description)
                            <p class="text-xs text-slate-500 pl-2 line-clamp-2 mb-2 leading-relaxed">
                                {{ $link->description }}
                            </p>
                            @endif
    
                            <div class="mt-auto pt-4 flex justify-between items-end pl-2">
                                <p class="text-xs text-slate-400 truncate max-w-[70%] font-mono">{{ parse_url($link->url, PHP_URL_HOST) ?? $link->url }}</p>
                                <span class="p-1.5 rounded-lg bg-slate-50 text-slate-400 group-hover:bg-blue-100 group-hover:text-blue-600 transition shadow-sm group-hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
            @empty
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="bg-slate-50 rounded-full w-24 h-24 flex items-center justify-center mb-6 shadow-inner ring-1 ring-slate-100">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Belum ada aplikasi</h3>
                <p class="text-slate-500 max-w-sm mx-auto">Aplikasi dan link yang ditambahkan admin akan muncul di sini.</p>
            </div>
            @endforelse

            <div x-show="search.length > 0 && document.querySelectorAll('.category-section a[x-show]:not([style*=\'display: none\'])').length === 0"
                class="flex flex-col items-center justify-center py-20 text-center" style="display: none;">
                <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-4 text-2xl">?</div>
                <h4 class="text-lg font-bold text-slate-900 mb-1">Tidak ditemukan</h4>
                <p class="text-slate-500 mb-4">Tidak ada hasil untuk kata kunci "<span x-text="search" class="font-semibold text-slate-800"></span>"</p>
                <button @click="search = ''" class="text-blue-600 hover:text-blue-800 text-sm font-semibold hover:underline">Reset Pencarian</button>
            </div>
        </div>
    </main>

    <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center sm:text-left border-t border-slate-200/50 mt-12 bg-white/30 backdrop-blur-sm">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-slate-400 text-sm font-medium">&copy; {{ date('Y') }} Portal Kerja BPS Kabupaten Dairi</p>
            <div class="flex gap-4">
                <a href="#" class="text-slate-400 hover:text-blue-600 transition text-sm">Bantuan</a>
                <a href="#" class="text-slate-400 hover:text-blue-600 transition text-sm">Panduan</a>
            </div>
        </div>
    </footer>

    <style type="text/tailwindcss">
        @keyframes wave {
            0% { transform: rotate(0deg); }
            10% { transform: rotate(14deg); }
            20% { transform: rotate(-8deg); }
            30% { transform: rotate(14deg); }
            40% { transform: rotate(-4deg); }
            50% { transform: rotate(10deg); }
            60% { transform: rotate(0deg); }
            100% { transform: rotate(0deg); }
        }
        .animate-wave {
            animation-name: wave;
            animation-duration: 2.5s;
            animation-iteration-count: infinite;
            transform-origin: 70% 70%;
            display: inline-block;
        }
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }
        .animate-shimmer {
            animation: shimmer 2s infinite;
        }
    </style>
</body>
</html>