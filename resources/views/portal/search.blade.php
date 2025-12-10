<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian - Portal Kerja</title>
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
                    <img src="/images/logo.png" alt="Logo" class="h-10 mr-3">
                    <h1 class="text-2xl font-bold text-gray-900">Portal Kerja BPS Kabupaten Dairi</h1>
                </div>

                <!-- Search Box -->
                <form action="{{ route('portal.search') }}" method="GET" class="flex-1 max-w-md mx-8">
                    <input type="text" name="q" placeholder="Cari link..."
                           value="{{ $query }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </form>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
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
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('portal') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Portal
            </a>
        </div>

        <!-- Search Results -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Hasil Pencarian untuk: <span class="text-blue-600">"{{ $query }}"</span>
            </h2>

            @if($links->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($links as $link)
                    <a href="{{ route('link.redirect', $link) }}"
                       class="flex items-start p-4 rounded-lg border border-gray-200 hover:border-gray-300 hover:shadow-sm transition-all duration-200"
                       @if($link->open_new_tab) target="_blank" @endif>
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-10 h-10 rounded-lg" style="background-color: {{ $link->color }}"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900">{{ $link->title }}</h4>
                            @if($link->description)
                            <p class="text-xs text-gray-500 mt-1">{{ $link->description }}</p>
                            @endif
                            <div class="flex items-center mt-2 text-xs text-gray-400">
                                <span>{{ $link->team->name }}</span>
                                @if($link->category)
                                <span class="mx-1">•</span>
                                <span>{{ $link->category->name }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="mt-4 text-gray-500">Tidak ada hasil yang ditemukan untuk pencarian Anda.</p>
                    <a href="{{ route('portal') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                        Kembali ke Portal
                    </a>
                </div>
            @endif
        </div>
    </main>

    <!-- Floating Button untuk Admin -->
    @if(Auth::user()->role === 'admin')
    <div class="fixed bottom-6 right-6 group">
        <a href="/admin" class="relative bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition-all hover:scale-110 block">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
            </svg>
            <span class="absolute right-full top-1/2 -translate-y-1/2 mr-2 px-3 py-1 text-sm text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                Manajemen Portal
            </span>
        </a>
    </div>
    @endif
</body>
</html>
