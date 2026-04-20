<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PODA - Portal Kerja BPS Kabupaten Dairi">
    <title>PODA - Portal Dairi</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Modern Font: Righteous -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        
        /* ===== Root Variables ===== */
        :root {
            --accent: #f97316;
            --accent-light: #fdba74;
            --dark-bg: #0c0e1a;
            --dark-surface: #141624;
        }

        /* ===== Full Page Photo Background ===== */
        .full-bg-container {
            position: fixed;
            inset: 0;
            z-index: -1;
            overflow: hidden;
            background-color: var(--dark-bg);
        }
        .full-bg-container .hero-bg-img {
            position: absolute;
            inset: -5%;
            background-position: 50% 70%;
            background-size: cover;
            background-repeat: no-repeat;
            filter: saturate(0.9);
            animation: heroZoom 30s ease-in-out infinite alternate;
        }
        @keyframes heroZoom {
            0% { transform: scale(1); }
            100% { transform: scale(1.06); }
        }
        .full-bg-container .hero-overlay {
            position: absolute;
            inset: -5%;
            background:
                linear-gradient(180deg, rgba(12,14,26,0.7) 0%, rgba(12,14,26,0.55) 35%, rgba(12,14,26,0.95) 100%),
                radial-gradient(ellipse 80% 50% at 50% 30%, rgba(249,115,22,0.15) 0%, transparent 60%);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        /* ===== Title ===== */
        .poda-title {
            font-family: 'Outfit', sans-serif !important;
            font-size: clamp(3.5rem, 10vw, 6.5rem);
            font-weight: 900 !important;
            letter-spacing: 0.02em;
            line-height: 1;
            color: #ffffff;
            text-transform: uppercase;
            text-shadow: 0 6px 30px rgba(0,0,0,0.5);
        }

        /* ===== Search ===== */
        .glass-search {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: all 0.3s ease;
        }
        .glass-search:focus-within {
            background: rgba(255,255,255,0.12);
            border-color: rgba(255,255,255,0.25);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        .glass-search:focus-within input { color: #ffffff; }
        .glass-search:focus-within input::placeholder { color: #94a3b8; }
        .glass-search:focus-within .search-icon { color: var(--accent); }
        .glass-search input,
        .glass-search input:focus {
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
            ring: none !important;
            color: #f8fafc;
        }

        /* ===== Link Card (Dark Glass) ===== */
        .link-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .link-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 16px;
            padding: 1px;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            transition: background 0.35s;
        }
        .link-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5), 0 0 0 1px rgba(249,115,22,0.25);
            border-color: rgba(249,115,22,0.4);
            background: rgba(255, 255, 255, 0.06);
        }
        .link-card:hover::before {
            background: linear-gradient(135deg, rgba(249,115,22,0.6), rgba(168,85,247,0.3));
        }
        .link-card .card-icon {
            background: rgba(255,255,255,0.05);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .link-card:hover .card-icon {
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            border-color: transparent;
            color: #fff;
        }
        .link-card:hover .card-title {
            color: var(--accent-light);
        }
        .link-card:hover .card-arrow {
            opacity: 1;
            transform: translate(0, 0);
        }

        /* ===== Scrollbar ===== */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.4); }

        /* ===== Glass Nav ===== */
        .glass-nav {
            background: rgba(12, 14, 26, 0.5);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .glass-nav.scrolled {
            background: rgba(12, 14, 26, 0.9);
            border-bottom-color: rgba(255,255,255,0.1);
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        /* ===== Animations ===== */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .anim-1 { animation: fadeUp 0.7s 0.1s ease-out both; }
        .anim-2 { animation: fadeUp 0.7s 0.25s ease-out both; }
        .anim-3 { animation: fadeUp 0.7s 0.4s ease-out both; }
        .anim-4 { animation: fadeUp 0.7s 0.55s ease-out both; }

        /* Staggered card fade-in */
        .link-card { opacity: 0; animation: fadeUp 0.5s ease-out both; }
        @media (prefers-reduced-motion: reduce) {
            .link-card, .anim-1, .anim-2, .anim-3, .anim-4 { animation: none; opacity: 1; }
        }

        /* Section title accent */
        .section-accent {
            position: relative;
        }
        .section-accent::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -6px;
            height: 3px;
            width: 40px;
            background: linear-gradient(90deg, var(--accent), transparent);
            border-radius: 2px;
        }

        /* Stats counter */
        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
        }
        .stat-pill .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #22c55e;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-transparent antialiased" style="font-family: 'Inter', system-ui, sans-serif;" x-data="{ search: '', scrolled: false }" @scroll.window="scrolled = window.scrollY > 60">

    <div class="full-bg-container">
        <div class="hero-bg-img" style="background-image: url('{{ asset('images/hero-bg.jpg') }}')"></div>
        <div class="hero-overlay"></div>
    </div>

    <!-- ===== Fixed Navigation ===== -->
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-500" :class="scrolled ? 'glass-nav scrolled' : 'glass-nav'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo BPS" class="h-9 w-auto">
                    <div>
                        <h1 class="font-extrabold leading-none tracking-tight text-base" style="font-family: 'Outfit', sans-serif;" :class="scrolled ? 'text-slate-800' : 'text-white'">PODA</h1>
                        <p class="text-[10px] font-bold tracking-[0.15em] uppercase" :class="scrolled ? 'text-slate-500' : 'text-slate-300'">Portal Dairi</p>
                    </div>
                </div>

                <a href="{{ route('login') }}" id="btn-login-nav"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm transition-all duration-300 shadow-lg hover:-translate-y-0.5"
                   :class="scrolled ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-orange-500/20 hover:shadow-orange-500/30' : 'bg-white/10 text-white border border-white/20 hover:bg-white/20 shadow-none backdrop-blur-sm'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Masuk Portal
                </a>
            </div>
        </div>
    </nav>

    <!-- ===== Hero Section ===== -->
    <section class="min-h-[45vh] flex flex-col items-center justify-center relative z-10 pt-28 pb-8">
        <div class="w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <!-- Main Title -->
            <h2 class="poda-title anim-2 mb-6">PODA</h2>

            <p class="anim-3 text-base sm:text-lg text-slate-300/80 max-w-md mx-auto leading-relaxed mb-8">
                Portal Kerja Badan Pusat Statistik Kabupaten Dairi
            </p>

            <!-- Search Bar -->
            <div class="anim-4 max-w-sm mx-auto">
                <div class="glass-search rounded-full">
                    <div class="flex items-center px-4">
                        <svg class="search-icon w-4 h-4 text-slate-400 shrink-0 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" x-model="search" id="search-input"
                               placeholder="Cari aplikasi..."
                               class="flex-1 bg-transparent py-2.5 px-3 text-white placeholder-slate-400 focus:outline-none text-[13px]"
                               @keydown.escape="search = ''">
                        <button x-show="search.length > 0" @click="search = ''" x-cloak
                                class="text-slate-400 hover:text-slate-300 cursor-pointer p-0.5 transition">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== Links Section ===== -->
    <section class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        

        <!-- Links Grid -->
        @if($links->isNotEmpty())
        <div class="flex flex-wrap justify-center gap-5" id="links-grid">
            @foreach($links as $index => $link)
            <a href="{{ route('link.redirect', $link) }}"
               target="{{ $link->target ?? '_blank' }}"
               class="w-full sm:w-[calc(50%_-_10px)] lg:w-[calc(33.333%_-_13.4px)] xl:w-[calc(25%_-_15px)] link-card p-4 flex items-center gap-4 group shrink-0"
               style="animation-delay: {{ min($index * 0.05, 0.8) }}s"
               data-title="{{ strtolower($link->title) }}"
               data-description="{{ strtolower($link->description ?? '') }}"
               x-show="search === '' || $el.dataset.title.includes(search.toLowerCase()) || $el.dataset.description.includes(search.toLowerCase())">

                <!-- Logo -->
                <div class="card-icon w-10 h-10 shrink-0 rounded-xl flex items-center justify-center transition-all duration-300 overflow-hidden">
                    @if($link->logo)
                    <img src="{{ asset('storage/' . $link->logo) }}" alt="" class="w-full h-full object-contain p-1.5 opacity-90 transition-all duration-300">
                    @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    @endif
                </div>

                <!-- Text -->
                <div class="min-w-0 flex-1">
                    <h4 class="card-title font-bold text-[15px] text-white truncate transition-colors duration-300 drop-shadow-md">{{ $link->title }}</h4>
                    @if($link->description)
                    <p class="text-[13px] text-slate-300 truncate leading-relaxed mt-0.5 drop-shadow-sm">{{ $link->description }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @endif

        <!-- Search Empty State -->
        <div x-show="search.length > 0" x-cloak>
            <template x-if="!document.querySelector('.link-card:not([style*=\'display: none\'])')">
                <div class="flex flex-col items-center justify-center py-24 text-center">
                    <div class="w-20 h-20 bg-[rgba(255,255,255,0.05)] border border-[rgba(255,255,255,0.1)] text-slate-400 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-200 mb-2">Tidak ditemukan</h4>
                    <p class="text-slate-400 mb-6">Tidak ada hasil untuk "<span x-text="search" class="font-semibold text-slate-300"></span>"</p>
                    <button @click="search = ''" class="px-5 py-2.5 bg-orange-500/20 text-orange-400 rounded-xl text-sm font-semibold hover:bg-orange-500/30 border border-orange-500/30 transition cursor-pointer">Reset Pencarian</button>
                </div>
            </template>
        </div>

        @if($links->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-20 h-20 bg-[rgba(255,255,255,0.05)] border border-[rgba(255,255,255,0.1)] text-slate-400 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-200 mb-2">Belum ada link publik</h3>
            <p class="text-slate-400 text-sm">Link publik akan muncul di sini setelah ditambahkan admin.</p>
        </div>
        @endif
    </section>

    <!-- ===== Footer ===== -->
    <footer class="bg-[rgba(12,14,26,0.6)] backdrop-blur-xl border-t border-[rgba(255,255,255,0.05)] mt-auto relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-[rgba(255,255,255,0.05)] rounded-xl flex items-center justify-center border border-[rgba(255,255,255,0.1)]">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo BPS" class="h-6 w-auto">
                    </div>
                    <div>
                        <p class="font-bold text-slate-200 text-sm">PODA</p>
                        <p class="text-xs text-slate-400">Portal Kerja BPS Kabupaten Dairi</p>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <p class="text-xs text-slate-500">&copy; {{ date('Y') }} BPS Kabupaten Dairi</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Keyboard shortcut
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('search-input').focus();
            }
        });
    </script>
</body>
</html>
