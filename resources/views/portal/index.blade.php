<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Kerja BPS Kabupaten Dairi</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo dan Title -->
                <div class="flex items-center">
                    <!-- LOGO -->
                    <img src="/images/logo.png" alt="Logo" class="h-10 mr-3">
                    <h1 class="text-2xl font-bold text-gray-900">Portal Kerja BPS Kabupaten Dairi</h1>
                </div>

                <!-- Search Box -->
                <!-- Search Box dengan Suggestions -->
                <form action="{{ route('portal.search') }}" method="GET" class="flex-1 max-w-md mx-8 relative" x-data="searchBox()">
                    <input
                        type="text"
                        name="q"
                        x-model="query"
                        @input="search()"
                        @focus="showDropdown = true"
                        @click.away="showDropdown = false"
                        placeholder="Cari link..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                    <!-- Dropdown Suggestions -->
                    <div
                        x-show="showDropdown && (results.length > 0 || (query.length > 0 && !loading))"
                        x-transition
                        class="absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-lg border border-gray-200 max-h-96 overflow-y-auto z-50">

                        <!-- Loading -->
                        <div x-show="loading" class="p-4 text-center text-gray-500">
                            <svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <!-- Results -->
                        <div x-show="!loading && results.length > 0">
                            <template x-for="link in results" :key="link.id">
                                <a
                                    :href="'/link/' + link.id"
                                    target="_blank"
                                    @click="showDropdown = false"
                                    class="flex items-start p-3 hover:bg-gray-50 border-b border-gray-100 last:border-0">
                                    <div class="flex-shrink-0 mr-3">
                                        <div class="w-8 h-8 rounded" :style="'background-color: ' + link.color"></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900" x-text="link.title"></h4>
                                        <p class="text-xs text-gray-500 mt-0.5" x-text="link.description"></p>
                                        <div class="flex items-center mt-1 text-xs text-gray-400">
                                            <span x-text="link.team_name"></span>
                                            <span class="mx-1" x-show="link.category_name">•</span>
                                            <span x-text="link.category_name"></span>
                                        </div>
                                    </div>
                                </a>
                            </template>

                            <!-- Show More Link -->
                            <div class="p-3 bg-gray-50 text-center border-t">
                                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                                    Lihat semua hasil untuk "<span x-text="query"></span>" →
                                </button>
                            </div>
                        </div>

                        <!-- No Results -->
                        <div x-show="!loading && results.length === 0 && query.length > 0" class="p-4 text-center text-gray-500">
                            <p>Tidak ada hasil untuk "<span x-text="query"></span>"</p>
                            <button type="submit" class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                                Coba pencarian lengkap →
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Alpine.js Script -->
                <script src="//unpkg.com/alpinejs" defer></script>
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
                                        this.results = data.slice(0, 5); // Hanya tampilkan 5 hasil teratas
                                        this.loading = false;
                                    })
                                    .catch(() => {
                                        this.loading = false;
                                    });
                            }, 300); // Delay 300ms untuk mengurangi request
                        }
                    }
                }
                </script>
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <!-- User Avatar (optional) -->
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white mr-2">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>


    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Announcements -->
        @if($announcements->count() > 0)
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Pengumuman</h2>
            <div class="space-y-3">
                @foreach($announcements as $announcement)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h3 class="font-medium text-gray-900">{{ $announcement->title }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $announcement->content }}</p>
                    <div class="flex items-center mt-2 text-xs text-gray-500">
                        <span>{{ $announcement->created_at->diffForHumans() }}</span>
                        @if($announcement->team)
                        <span class="mx-2">•</span>
                        <span class="bg-gray-200 px-2 py-1 rounded">{{ $announcement->team->name }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Quick Access Section -->
        @if($recentLinks->count() > 0)
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Akses Cepat</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($recentLinks as $link)
                <a href="{{ route('link.redirect', $link) }}"
                class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow duration-200 border border-gray-200"
                @if($link->open_new_tab) target="_blank" @endif>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full mr-3" style="background-color: {{ $link->color }}"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $link->title }}</p>
                            <p class="text-xs text-gray-500">{{ $link->team->name }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Teams and Links -->
        <div class="grid gap-8">
            @foreach($teams as $team)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                    <h2 class="text-xl font-semibold text-blue-700">{{ $team->name }}</h2>
                    @if($team->description)
                    <p class="text-sm text-gray-600 mt-1">{{ $team->description }}</p>
                    @endif
                </div>

                <div class="p-6">
                    @if($team->links->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($team->links as $link)
                            <a href="{{ route('link.redirect', $link) }}"
                               class="flex items-start p-4 rounded-lg border border-gray-200 hover:border-gray-300 hover:shadow-sm transition-all duration-200"
                               @if($link->open_new_tab) target="_blank" @endif>
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-500"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $link->title }}</h4>
                                    @if($link->description)
                                    <p class="text-xs text-gray-500 mt-1">{{ $link->description }}</p>
                                    @endif
                                    @if($link->category)
                                    <span class="text-xs text-gray-400 mt-1">{{ $link->category->name }}</span>
                                    @endif
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Belum ada link untuk tim ini.</p>
                    @endif
                </div>
            </div>
            @endforeach

            @if($teams->count() == 0)
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-500">Anda belum terdaftar di tim manapun.</p>
            </div>
            @endif
        </div>
    </main>
</body>
<!-- Tambah di bagian bawah body portal -->
@if(Auth::user()->role === 'admin')
<div class="fixed bottom-6 right-6 group">
    <a href="/admin" class="relative bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition-all hover:scale-110 block">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
        </svg>
        <!-- Tooltip dengan arrow -->
        <div class="absolute right-full top-1/2 -translate-y-1/2 mr-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <div class="bg-gray-800 text-white text-sm rounded-lg py-1 px-3 whitespace-nowrap relative">
                Manajemen Portal
                <!-- Arrow -->
                <div class="absolute -right-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-gray-800 rotate-45"></div>
            </div>
        </div>
    </a>
</div>
@endif
</html>
