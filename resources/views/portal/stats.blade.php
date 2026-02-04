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
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fffbf7',
                            100: '#fef3e6',
                            200: '#fce3c7',
                            300: '#facd9f',
                            400: '#f8b170',
                            500: '#f79039', // Base
                            600: '#e87221',
                            700: '#c15518',
                            800: '#99431a',
                            900: '#7c3818',
                        }
                    }
                }
            }
        }
    </script>
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
                Kembali ke Portal
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
                <div class="p-3 bg-primary-50 text-primary-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rata-rata Usia</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $avgAge }} <span class="text-sm font-normal text-gray-400">Tahun</span></h3>
                </div>
                <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="bg-gradient-to-br from-primary-600 to-orange-700 p-5 rounded-xl shadow-lg text-white flex flex-col justify-center">
                <p class="text-primary-100 text-xs font-medium uppercase tracking-wider">Hari ini</p>
                <h3 class="text-xl font-bold mt-1">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-2 h-6 bg-primary-500 rounded-full"></span> Pangkat/Golongan
                </h4>
                <div class="h-[400px]"><canvas id="chartGolongan"></canvas></div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-2 h-6 bg-purple-500 rounded-full"></span> Masa Pengabdian
                </h4>
                <div class="h-[400px]"><canvas id="chartMasaKerja"></canvas></div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-2 text-xs text-center uppercase tracking-wide text-gray-500">Rentang Usia</h4>
                <div class="h-[300px] flex justify-center"><canvas id="chartUmur"></canvas></div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-2 text-xs text-center uppercase tracking-wide text-gray-500">Pendidikan</h4>
                <div class="h-[300px] flex justify-center"><canvas id="chartPendidikan"></canvas></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" 
             x-data="employeeDirectory">
             
            <div class="lg:col-span-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 space-y-4">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <h3 class="text-lg font-bold text-gray-800">Direktori Pegawai</h3>
                            
                            <div class="flex items-center gap-3 w-full sm:w-auto">
                                <select x-model="perPage" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2">
                                    <option value="20">20 Baris</option>
                                    <option value="50">50 Baris</option>
                                    <option value="100">100 Baris</option>
                                    <option value="all">Tampilkan Semua</option>
                                </select>

                                <div class="relative w-full sm:w-64">
                                    <input type="text" x-model.debounce.500ms="search" placeholder="Cari Nama / NIP..." 
                                        class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-primary-500 transition text-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <div x-show="isLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <svg class="animate-spin h-4 w-4 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-2">
                            <select x-model="jabatan" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                <option value="">Semua Jabatan</option>
                                @foreach($filterJabatan as $j)
                                <option value="{{ $j }}">{{ $j }}</option>
                                @endforeach
                            </select>

                            <select x-model="pangkat" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                <option value="">Semua Pangkat</option>
                                @foreach($filterPangkat as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                                @endforeach
                            </select>

                            <select x-model="masaKerja" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                <option value="">Semua Masa Pengabdian</option>
                                <option value="lt5">< 5 Tahun</option>
                                <option value="5-10">5 - 10 Tahun</option>
                                <option value="10-20">10 - 20 Tahun</option>
                                <option value="gt20">> 20 Tahun</option>
                            </select>

                            <select x-model="pendidikan" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                <option value="">Semua Pendidikan</option>
                                <option value="SMA/SMK">SMA/SMK</option>
                                <option value="D-I">D-I</option>
                                <option value="D-II">D-II</option>
                                <option value="D-III">D-III</option>
                                <option value="D-IV">D-IV</option>
                                <option value="S-1">S-1</option>
                                <option value="S-2">S-2</option>
                                <option value="S-3">S-3</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto min-h-[400px]" id="employee-table-container">
                        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                             <svg class="w-10 h-10 animate-spin text-primary-200 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                             <p>Memuat data pegawai...</p>
                        </div>
                    </div>
        </div>

        <div x-show="userModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition>
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="userModal = null"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all">
                    <div class="bg-gray-800 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            Biodata Lengkap
                        </h3>
                        <button @click="userModal = null" class="text-gray-400 hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 bg-gray-50/50 max-h-[75vh] overflow-y-auto">
                        <div class="flex items-center gap-5 mb-8 pb-6 border-b border-gray-200">
                            <div class="h-20 w-20 rounded-full bg-primary-600 text-white flex items-center justify-center text-3xl font-bold shadow-lg ring-4 ring-primary-50">
                                <span x-text="userModal?.name.charAt(0)"></span>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900" x-text="userModal?.name"></h2>
                                <p class="text-sm text-gray-500 mb-2" x-text="userModal?.email"></p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-primary-100 text-primary-700" x-text="userModal?.jabatan"></span>
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
                                    <span class="col-span-2 text-sm font-mono font-medium text-primary-700 bg-primary-50 px-2 rounded w-max" x-text="userModal?.nip_lama"></span>
                                </div>
                                <div class="grid grid-cols-3 gap-1">
                                    <span class="text-xs text-gray-500">TMT Kerja</span>
                                    <span class="col-span-2 text-sm font-medium text-gray-800" x-text="userModal?.tmt"></span>
                                </div>
                                <div class="grid grid-cols-3 gap-1">
                                    <span class="text-xs text-gray-500">Masa Kerja</span>
                                    <span class="col-span-2 text-sm font-bold text-green-600" x-text="userModal?.masa_kerja"></span>
                                </div>
                                <div class="grid grid-cols-3 gap-1">
                                    <span class="text-xs text-gray-500">Pendidikan</span>
                                    <span class="col-span-2 text-sm font-medium text-gray-800" x-text="userModal?.pendidikan"></span>
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
                scales: { 
                    y: { 
                        beginAtZero: true, 
                        ticks: { stepSize: 1, font: { size: 11 } } 
                    }, 
                    x: { 
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    } 
                }
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
                scales: { 
                    x: { beginAtZero: true },
                    y: {
                        ticks: {
                            autoSkip: false, // Force show all labels
                            font: { size: 11 } // Increase font size
                        }
                    }
                }
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
                scales: { 
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 12 } } },
                    x: { ticks: { font: { size: 12 }, autoSkip: false } }
                }
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
                scales: { 
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 12 } } },
                    x: { ticks: { font: { size: 12 }, autoSkip: false } }
                }
            }
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('employeeDirectory', () => ({
                search: '{{ request('search') }}',
                sort: '{{ request('sort', 'name') }}',
                direction: '{{ request('direction', 'asc') }}',
                perPage: '20',
                page: 1,
                
                // New Filters
                jabatan: '',
                pangkat: '',
                masaKerja: '',
                pendidikan: '',

                isLoading: false,
                
                async fetchData() {
                    this.isLoading = true;
                    try {
                        const params = new URLSearchParams({
                            search: this.search,
                            sort: this.sort,
                            direction: this.direction,
                            per_page: this.perPage,
                            page: this.page,
                            // Send filters
                            jabatan: this.jabatan,
                            pangkat: this.pangkat,
                            masa_kerja: this.masaKerja,
                            pendidikan: this.pendidikan
                        });

                        const response = await fetch(`{{ route('portal.employees.search') }}?${params.toString()}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const html = await response.text();
                        
                        document.querySelector('#employee-table-container').innerHTML = html;
                        
                        // Re-initialize pagination links
                        this.$nextTick(() => {
                            const container = document.querySelector('#employee-table-container');
                            if (!container) return;

                            const paginationLinks = container.querySelectorAll('a.page-link'); 
                            if (paginationLinks.length > 0) {
                                paginationLinks.forEach(link => {
                                    link.addEventListener('click', (e) => {
                                        e.preventDefault();
                                        const url = new URL(link.href);
                                        const page = url.searchParams.get('page');
                                        if (page) {
                                            this.page = page;
                                            this.window.scrollTo({top: document.querySelector('#employee-table-container').offsetTop - 100, behavior: 'smooth'});
                                            this.fetchData();
                                        }
                                    });
                                });
                            }
                            
                            const tailwindLinks = container.querySelectorAll('nav[role="navigation"] a');
                            if (tailwindLinks.length > 0) {
                                tailwindLinks.forEach(link => {
                                    link.addEventListener('click', (e) => {
                                        e.preventDefault();
                                        const url = new URL(link.href);
                                        const page = url.searchParams.get('page');
                                        if (page) {
                                            this.page = page;
                                            this.fetchData();
                                        }
                                    });
                                });
                            }
                        });
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },
                init() {
                    this.fetchData();
                    
                    ['search', 'perPage', 'jabatan', 'pangkat', 'masaKerja', 'pendidikan'].forEach(prop => {
                        this.$watch(prop, () => {
                            this.page = 1; 
                            this.fetchData(); 
                        });
                    });
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
            }));
        });
    </script>
</body>
</html>