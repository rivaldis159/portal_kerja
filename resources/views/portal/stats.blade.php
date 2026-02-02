<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>

<body class="h-full text-gray-800 bg-gray-100/50" x-data="{ userModal: null }">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-9 w-auto">
                <div>
                    <h1 class="font-bold text-gray-800 leading-none text-lg">Dashboard Pegawai</h1>
                    <span class="text-[10px] text-gray-500 font-medium tracking-wider uppercase">BPS KABUPATEN DAIRI</span>
                </div>
            </div>
            <a href="{{ route('portal.index') }}" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-sm font-medium transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Portal Menu
            </a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between relative overflow-hidden group">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pegawai</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalPegawai }}</h3>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rata-rata Umur</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $avgAge }} <span class="text-sm font-normal text-gray-400">Tahun</span></h3>
                </div>
                <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-5 rounded-xl shadow-lg text-white flex flex-col justify-center">
                <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Hari ini</p>
                <h3 class="text-xl font-bold mt-1">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 lg:col-span-2">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-500 rounded-full"></span> Pangkat/Golongan
                </h4>
                <div class="h-56"><canvas id="chartGolongan"></canvas></div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 lg:col-span-2">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-2 h-6 bg-purple-500 rounded-full"></span> Masa Pengabdian
                </h4>
                <div class="h-56"><canvas id="chartMasaKerja"></canvas></div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 lg:col-span-1">
                <h4 class="font-bold text-gray-800 mb-2 text-xs text-center uppercase tracking-wide text-gray-500">Rentang Usia</h4>
                <div class="h-40 flex justify-center"><canvas id="chartUmur"></canvas></div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 lg:col-span-1">
                <h4 class="font-bold text-gray-800 mb-2 text-xs text-center uppercase tracking-wide text-gray-500">Pendidikan</h4>
                <div class="h-40 flex justify-center"><canvas id="chartPendidikan"></canvas></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" 
             x-data="{ 
                search: '{{ request('search') }}',
                sort: '{{ request('sort', 'name') }}',
                direction: '{{ request('direction', 'asc') }}',
                perPage: '20',
                isLoading: false,
                htmlContent: '',
                
                async fetchData() {
                    this.isLoading = true;
                    const params = new URLSearchParams({
                        search: this.search,
                        sort: this.sort,
                        direction: this.direction,
                        per_page: this.perPage
                    });

                    try {
                        const response = await fetch(`{{ route('portal.employees.search') }}?${params.toString()}`);
                        this.htmlContent = await response.text();
                        
                        // Update Pagination render if needed (logic handled by partial returning rows + hidden pagination data if simple)
                        // Note: For full pagination update we might need a better strategy, 
                        // but for 'Show All' vs 'Paginate', checking the hidden row is a simple trick or just replacing the whole table body.
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                init() {
                    this.fetchData();
                    
                    // Watchers
                    this.$watch('search', () => { setTimeout(() => this.fetchData(), 500) }); // Debounce 500ms manually roughly
                    this.$watch('perPage', () => this.fetchData());
                },
                setSort(column) {
                    if (this.sort === column) {
                        this.direction = this.direction === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sort = column;
                        this.direction = 'asc';
                    }
                    this.fetchData();
                }
             }">
             
            <div class="lg:col-span-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <h3 class="text-lg font-bold text-gray-800">Direktori Pegawai</h3>
                        
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <!-- Dropdown Rows -->
                            <select x-model="perPage" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                                <option value="20">20 Baris</option>
                                <option value="50">50 Baris</option>
                                <option value="100">100 Baris</option>
                                <option value="all">Tampilkan Semua</option>
                            </select>

                            <!-- Search Input -->
                            <div class="relative w-full sm:w-64">
                                <input type="text" x-model.debounce.500ms="search" placeholder="Cari Nama / NIP..." 
                                    class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-blue-500 transition text-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <div x-show="isLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto min-h-[400px]">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50/50 text-gray-500 font-medium border-b border-gray-200 uppercase tracking-wider text-xs">
                                <tr>
                                    <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition group select-none" @click="setSort('name')">
                                        <div class="flex items-center gap-1">
                                            Pegawai
                                            <span x-show="sort === 'name'" :class="{'text-blue-600': true}" x-text="direction === 'asc' ? '▲' : '▼'"></span>
                                            <span x-show="sort !== 'name'" class="text-gray-300 opacity-0 group-hover:opacity-100 transition">⇅</span>
                                        </div>
                                    </th>
                                    
                                    <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition group select-none" @click="setSort('jabatan')">
                                        <div class="flex items-center gap-1">
                                            Jabatan & Pangkat
                                            <span x-show="sort === 'jabatan'" :class="{'text-blue-600': true}" x-text="direction === 'asc' ? '▲' : '▼'"></span>
                                            <span x-show="sort !== 'jabatan'" class="text-gray-300 opacity-0 group-hover:opacity-100 transition">⇅</span>
                                        </div>
                                    </th>

                                    <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition group select-none" @click="setSort('masa_kerja')">
                                        <div class="flex items-center gap-1">
                                            Masa Kerja
                                            <span x-show="sort === 'masa_kerja'" :class="{'text-blue-600': true}" x-text="direction === 'asc' ? '▲' : '▼'"></span>
                                            <span x-show="sort !== 'masa_kerja'" class="text-gray-300 opacity-0 group-hover:opacity-100 transition">⇅</span>
                                        </div>
                                    </th>

                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" x-html="htmlContent">
                                <!-- Data Loaded via AJAX -->
                                <tr><td colspan="4" class="text-center py-10 text-gray-400">Memuat data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-4 border-t border-gray-200 bg-gray-50/50 text-xs text-gray-500 text-center">
                        <span x-show="perPage !== 'all'">Menampilkan maksimal <span x-text="perPage"></span> data per halaman.</span>
                        <span x-show="perPage === 'all'">Menampilkan <strong>seluruh data</strong> pegawai.</span>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="userModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition>
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="userModal = null"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all">
                    <div class="bg-gray-800 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            Biodata Lengkap
                        </h3>
                        <button @click="userModal = null" class="text-gray-400 hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 bg-gray-50/50 max-h-[75vh] overflow-y-auto">
                        <div class="flex items-center gap-5 mb-8 pb-6 border-b border-gray-200">
                            <div class="h-20 w-20 rounded-full bg-blue-600 text-white flex items-center justify-center text-3xl font-bold shadow-lg ring-4 ring-blue-50">
                                <span x-text="userModal?.name.charAt(0)"></span>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900" x-text="userModal?.name"></h2>
                                <p class="text-sm text-gray-500 mb-2" x-text="userModal?.email"></p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-700" x-text="userModal?.jabatan"></span>
                                    <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-purple-100 text-purple-700" x-text="userModal?.pangkat"></span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="space-y-4">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b pb-1">Identitas Kepegawaian</h4>
                                <div class="grid grid-cols-3 gap-1">
                                    <span class="text-xs text-gray-500">NIP Baru</span>
                                    <span class="col-span-2 text-sm font-mono font-medium text-gray-800 bg-gray-100 px-2 rounded w-max" x-text="userModal?.nip"></span>
                                </div>
                                <div class="grid grid-cols-3 gap-1">
                                    <span class="text-xs text-gray-500">NIP Lama</span>
                                    <span class="col-span-2 text-sm font-mono font-medium text-blue-700 bg-blue-50 px-2 rounded w-max" x-text="userModal?.nip_lama"></span>
                                </div>
                                <div class="grid grid-cols-3 gap-1">
                                    <span class="text-xs text-gray-500">TMT Kerja</span>
                                    <span class="col-span-2 text-sm font-medium text-gray-800" x-text="userModal?.tmt"></span>
                                </div>
                                <div class="grid grid-cols-3 gap-1">
                                    <span class="text-xs text-gray-500">Masa Kerja</span>
                                    <span class="col-span-2 text-sm font-bold text-green-600" x-text="userModal?.masa_kerja"></span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b pb-1">Data Pribadi</h4>
                                <div class="mb-2">
                                    <span class="block text-xs text-gray-500 mb-0.5">NIK (KTP)</span>
                                    <span class="text-sm font-medium text-gray-800" x-text="userModal?.nik"></span>
                                </div>
                                <div class="mb-2">
                                    <span class="block text-xs text-gray-500 mb-0.5">Tempat, Tanggal Lahir</span>
                                    <span class="text-sm font-medium text-gray-800" x-text="userModal?.lahir"></span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-500 mb-0.5">Alamat Domisili</span>
                                    <span class="text-sm font-medium text-gray-800 leading-snug" x-text="userModal?.alamat"></span>
                                </div>
                            </div>

                            <div class="sm:col-span-2 mt-2 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                <h4 class="text-xs font-bold text-yellow-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    Data Keuangan
                                </h4>
                                <div class="flex flex-col sm:flex-row gap-6">
                                    <div>
                                        <span class="block text-xs text-yellow-600 mb-0.5">Nama Bank</span>
                                        <span class="text-sm font-bold text-gray-900" x-text="userModal?.bank_name"></span>
                                    </div>
                                    <div>
                                        <span class="block text-xs text-yellow-600 mb-0.5">Nomor Rekening</span>
                                        <span class="text-lg font-mono font-bold text-gray-900 tracking-wide bg-white px-2 rounded border border-yellow-100" x-text="userModal?.rekening"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-100 px-6 py-4 flex justify-end">
                        <button @click="userModal = null" class="bg-white border border-gray-300 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        const golonganData = @json($golonganStats);
        const pendidikanData = @json($pendidikanStats);
        const umurData = @json($umurStats);
        const masaKerjaData = @json($masaKerjaStats);

        // 1. Chart MASA KERJA
        new Chart(document.getElementById('chartMasaKerja'), {
            type: 'bar',
            data: {
                labels: Object.keys(masaKerjaData),
                datasets: [{
                    label: 'Jumlah Pegawai',
                    data: Object.values(masaKerjaData),
                    backgroundColor: ['#c084fc', '#a855f7', '#9333ea', '#7e22ce'],
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } }
            }
        });

        // 2. Chart Golongan
        new Chart(document.getElementById('chartGolongan'), {
            type: 'bar',
            data: {
                labels: Object.keys(golonganData),
                datasets: [{
                    label: 'Jumlah Pegawai',
                    data: Object.values(golonganData),
                    backgroundColor: '#3b82f6',
                    borderRadius: 4,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } }
            }
        });

        // 3. Chart Pendidikan
        new Chart(document.getElementById('chartPendidikan'), {
            type: 'bar',
            data: {
                labels: Object.keys(pendidikanData),
                datasets: [{
                    label: 'Jumlah Pegawai',
                    data: Object.values(pendidikanData),
                    backgroundColor: ['#10b981', '#f59e0b', '#6366f1', '#ec4899', '#8b5cf6'],
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // 4. Chart Umur
        new Chart(document.getElementById('chartUmur'), {
            type: 'bar',
            data: {
                labels: Object.keys(umurData),
                datasets: [{
                    label: 'Jumlah Pegawai',
                    data: Object.values(umurData),
                    backgroundColor: ['#3b82f6', '#8b5cf6', '#f43f5e', '#64748b'],
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    </script>
</body>
</html>