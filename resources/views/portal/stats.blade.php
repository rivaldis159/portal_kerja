<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Pegawai - Portal Kerja BPS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="h-full text-gray-800 bg-gray-100/50">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                <div class="flex flex-col">
                    <span class="font-bold text-gray-700 leading-tight">Dashboard Kepegawaian</span>
                    <span class="text-[10px] text-gray-500 uppercase tracking-wide">Monitoring SDM</span>
                </div>
            </div>
            <a href="{{ route('portal.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Portal
            </a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Pegawai</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $totalPegawai }} <span class="text-sm font-normal text-gray-400">Orang</span></h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex items-center gap-4">
                <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Jumlah Tim Kerja</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $totalTim }} <span class="text-sm font-normal text-gray-400">Tim</span></h3>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-400 to-amber-500 p-6 rounded-xl shadow-sm text-white flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm font-medium">Tanggal Hari Ini</p>
                    <h3 class="text-xl font-bold">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</h3>
                </div>
                <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Komposisi Jabatan</h3>
                <div class="h-64 flex justify-center">
                    <canvas id="chartJabatan"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Sebaran Pangkat/Golongan</h3>
                <div class="h-64 w-full">
                    <canvas id="chartGolongan"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                <h3 class="text-lg font-bold text-gray-800">Daftar Pegawai</h3>
                
                <form action="{{ route('portal.stats') }}" method="GET" class="w-full sm:w-72">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NIP..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-800 font-semibold border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Nama Pegawai</th>
                            <th class="px-6 py-4">NIP & Jabatan</th>
                            <th class="px-6 py-4">Pangkat/Gol.</th>
                            <th class="px-6 py-4">Keanggotaan Tim</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($employees as $emp)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($emp->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $emp->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $emp->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-mono text-xs text-gray-500 mb-1">
                                    {{ $emp->employeeDetail->nip ?? '-' }}
                                </p>
                                <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded text-xs font-semibold border border-green-100">
                                    {{ $emp->employeeDetail->jabatan ?? 'Staf' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($emp->employeeDetail?->pangkat_golongan)
                                    <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-bold border border-gray-200">
                                        {{ $emp->employeeDetail->pangkat_golongan }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($emp->teams as $team)
                                        <span class="text-[10px] px-2 py-1 rounded bg-blue-50 text-blue-600 border border-blue-100" style="color: {{ $team->color }}; border-color: {{ $team->color }}40; background-color: {{ $team->color }}10;">
                                            {{ Str::limit($team->name, 15) }}
                                        </span>
                                    @empty
                                        <span class="text-gray-300 text-xs italic">Tidak ada tim</span>
                                    @endforelse
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                Tidak ada data pegawai ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-gray-200">
                {{ $employees->withQueryString()->links() }}
            </div>
        </div>
    </main>

    <script>
        // Data dari Controller Laravel
        const jabatanData = @json($jabatanStats);
        const golonganData = @json($golonganStats);

        // 1. Grafik Jabatan (Doughnut)
        new Chart(document.getElementById('chartJabatan'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(jabatanData),
                datasets: [{
                    data: Object.values(jabatanData),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#6366f1'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });

        // 2. Grafik Golongan (Bar)
        new Chart(document.getElementById('chartGolongan'), {
            type: 'bar',
            data: {
                labels: Object.keys(golonganData),
                datasets: [{
                    label: 'Jumlah Pegawai',
                    data: Object.values(golonganData),
                    backgroundColor: '#6366f1',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
</body>
</html>