<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Kerja BPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full text-gray-800 bg-gray-50/50" x-data="{ search: '' }">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo BPS" class="h-10 w-auto">
                
                <div class="flex flex-col">
                    <span class="font-bold text-gray-800 text-lg leading-tight">Portal Kerja</span>
                    <span class="text-[10px] font-semibold text-blue-600 tracking-widest uppercase">BPS Kabupaten Dairi</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 hover:bg-gray-50 p-2 rounded-lg transition group">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-sm ring-2 ring-white">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="text-left hidden sm:block">
                                <p class="text-sm font-semibold text-gray-700 group-hover:text-blue-600">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wide">{{ auth()->user()->employeeDetail->jabatan ?? 'Pegawai' }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-1 origin-top-right transform transition-all z-50">
                            <div class="px-4 py-3 border-b border-gray-50">
                                <p class="text-xs text-gray-400 uppercase font-semibold">Akun Saya</p>
                                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('portal.profile') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Edit Profil & Biodata
                            </a>
                            
                            @if(auth()->user()->isSuperAdmin() || auth()->user()->teams()->wherePivot('role', 'admin')->exists())
                                <a href="/admin" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Panel Admin
                                </a>
                            @endif
                            
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Keluar (Logout)
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-lg shadow-blue-600/20">Login Pegawai</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="mb-10 flex flex-col md:flex-row justify-between items-end gap-6">
            @auth
                <div class="w-full md:w-auto">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
                    <p class="text-gray-500 mt-2 text-sm">Akses cepat aplikasi dan link pendukung kinerja.</p>
                </div>
            @else
                 <div class="w-full md:w-auto">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Selamat Datang 👋</h1>
                    <p class="text-gray-500 mt-2 text-sm">Silakan login untuk akses fitur lengkap.</p>
                </div>
            @endauth

            <div class="w-full md:w-96 relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-model="search" 
                    placeholder="Cari aplikasi atau link..." 
                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-sm transition"
                >
                <button 
                    x-show="search.length > 0" 
                    @click="search = ''" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer"
                    style="display: none;">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        @if(isset($announcements) && $announcements->count() > 0)
            <div class="mb-10" x-show="search === ''">
                <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="flex items-start gap-4 relative z-10">
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg mb-1">Pengumuman Penting</h3>
                            <div class="space-y-3">
                                @foreach($announcements as $announcement)
                                    <div class="border-l-2 border-white/40 pl-3">
                                        <p class="font-medium text-sm text-white/90 mb-0.5">{{ $announcement->title }}</p>
                                        <p class="text-xs text-white/70">{{ Str::limit($announcement->content, 100) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-12">
            @forelse($categories as $category)
                @if($category->links->isNotEmpty())
                    <div x-data="{ hasVisibleLinks: true }" x-show="hasVisibleLinks" class="category-section">
                        <div class="flex items-center gap-3 mb-5 border-b border-gray-200 pb-2">
                            <span class="bg-blue-100 text-blue-700 p-1.5 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            </span>
                            <h2 class="text-xl font-bold text-gray-800">{{ $category->name }}</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                            @foreach($category->links as $link)
                                <a 
                                    href="{{ $link->url }}" 
                                    target="{{ $link->target ?? '_blank' }}" 
                                    class="group bg-white rounded-xl shadow-sm hover:shadow-md border border-gray-100 p-5 transition-all hover:-translate-y-1 flex flex-col h-full relative overflow-hidden"
                                    data-title="{{ strtolower($link->title) }}"
                                    x-show="search === '' || $el.dataset.title.includes(search.toLowerCase())"
                                >
                                    
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $link->is_vpn_required ? 'bg-red-500' : 'bg-blue-500' }}"></div>
                                    
                                    <div class="flex justify-between items-start mb-2 pl-2">
                                        <div class="flex items-center gap-2">
                                            @if($link->is_bps_pusat)
                                                <span class="text-[10px] font-bold bg-gray-100 text-gray-600 px-2 py-0.5 rounded uppercase tracking-wider">PUSAT</span>
                                            @elseif($link->team)
                                                <span class="text-[10px] font-bold bg-blue-50 text-blue-600 px-2 py-0.5 rounded uppercase tracking-wider" style="color: {{ $link->team->color }}; background-color: {{ $link->team->color }}15;">
                                                    {{ Str::limit($link->team->name, 15) }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($link->is_vpn_required)
                                            <span class="flex items-center gap-1 text-[10px] font-bold bg-red-100 text-red-600 px-2 py-0.5 rounded border border-red-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                WAJIB VPN
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-base font-semibold text-gray-800 group-hover:text-blue-600 transition pl-2 line-clamp-2">
                                        {{ $link->title }}
                                    </h3>
                                    
                                    <p class="text-xs text-gray-400 mt-2 pl-2 truncate">{{ $link->url }}</p>
                                    
                                    <div class="mt-auto pt-3 flex justify-end">
                                        <span class="p-1 rounded-full bg-gray-50 text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-20">
                    <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada link tersedia</h3>
                    <p class="text-gray-500">Hubungi admin untuk menambahkan link.</p>
                </div>
            @endforelse
            
            <div x-show="search.length > 0 && document.querySelectorAll('.category-section a[x-show]:not([style*=\'display: none\'])').length === 0" 
                 class="text-center py-12" style="display: none;">
                <p class="text-gray-500">Tidak ditemukan link dengan kata kunci "<span x-text="search" class="font-semibold text-gray-800"></span>"</p>
                <button @click="search = ''" class="text-blue-600 hover:text-blue-700 text-sm mt-2 font-medium">Reset Pencarian</button>
            </div>
        </div>
    </main>

    <footer class="text-center py-8 text-gray-400 text-sm border-t border-gray-200 mt-10">
        &copy; {{ date('Y') }} Portal Kerja BPS Kabupaten Dairi.
    </footer>

</body>
</html>