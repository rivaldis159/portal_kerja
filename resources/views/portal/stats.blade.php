<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Eksekutif SDM - BPS</title>
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
                    <h1 class="font-bold text-gray-800 leading-none text-lg">Dashboard SDM</h1>
                    <span class="text-[10px] text-gray-500 font-medium tracking-wider uppercase">Pusat Data Kepegawaian</span>
                </div>
            </div>
            <a href="{{ route('portal.index') }}" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 text-sm font-medium transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Portal Menu
            </a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ultah Bulan Ini</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $birthdayUsers->count() }} <span class="text-sm font-normal text-gray-400">Orang</span></h3>
                </div>
                <div class="p-3 bg-pink-50 text-pink-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path></svg>
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

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <h3 class="text-lg font-bold text-gray-800">Direktori Pegawai</h3>
                        <form action="{{ route('portal.stats') }}" method="GET" class="w-full sm:w-64">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NIP..." class="w-full pl-4 pr-10 py-2 rounded-lg bg-gray-50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-blue-500 transition text-sm">
                            @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                            @if(request('direction')) <input type="hidden" name="direction" value="{{ request('direction') }}"> @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50/50 text-gray-500 font-medium border-b border-gray-200 uppercase tracking-wider text-xs">
                                <tr>
                                    <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition group" 
                                        onclick="window.location.href='{{ route('portal.stats', ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}'">
                                        <div class="flex items-center gap-1">
                                            Pegawai
                                            @if(request('sort') == 'name')
                                                <span class="text-blue-600">{{ request('direction') == 'asc' ? '▲ (A-Z)' : '▼ (Z-A)' }}</span>
                                            @else
                                                <span class="text-gray-300 opacity-0 group-hover:opacity-100 transition">⇅</span>
                                            @endif
                                        </div>
                                    </th>
                                    
                                    <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition group"
                                        onclick="window.location.href='{{ route('portal.stats', ['sort' => 'jabatan', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}'">
                                        <div class="flex items-center gap-1">
                                            Jabatan & Pangkat
                                            @if(request('sort') == 'jabatan')
                                                <span class="text-blue-600">{{ request('direction') == 'asc' ? '▲ (Tinggi)' : '▼ (Rendah)' }}</span>
                                            @else
                                                <span class="text-gray-300 opacity-0 group-hover:opacity-100 transition">⇅</span>
                                            @endif
                                        </div>
                                    </th>

                                    <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition group"
                                         onclick="window.location.href='{{ route('portal.stats', ['sort' => 'masa_kerja', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}'">
                                        <div class="flex items-center gap-1">
                                            Masa Kerja
                                            @if(request('sort') == 'masa_kerja')
                                                <span class="text-blue-600">{{ request('direction') == 'asc' ? '▲ (Lama)' : '▼ (Baru)' }}</span>
                                            @else
                                                <span class="text-gray-300 opacity-0 group-hover:opacity-100 transition">⇅</span>
                                            @endif
                                        </div>
                                    </th>

                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($employees as $emp)
                                    @php
                                        // HITUNG MASA KERJA
                                        $masaKerjaText = '-';
                                        $masaKerjaDetail = '-';
                                        // Guna employeeDetail->nip karena controller select('users.*')
                                        $nip = $emp->employeeDetail->nip ?? null;
                                        
                                        if($nip && strlen($nip) == 18) {
                                            try {
                                                $tmtStr = substr($nip, 8, 6);
                                                $tmt = \Carbon\Carbon::createFromFormat('Ym', $tmtStr);
                                                $diff = $tmt->diff(now());
                                                $masaKerjaText = $diff->y . ' Thn ' . $diff->m . ' Bln';
                                                $masaKerjaDetail = $tmt->isoFormat('MMMM Y');
                                            } catch(\Exception $e) {}
                                        }
                                    @endphp
                                <tr class="hover:bg-blue-50/30 transition group">
                                    <td class="px-6 py-4 align-top">
                                        <div class="flex items-start gap-3">
                                            <div class="h-10 w-10 mt-1 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center text-gray-600 font-bold shadow-inner shrink-0">
                                                {{ strtoupper(substr($emp->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900 text-sm">{{ $emp->name }}</p>
                                                <div class="flex items-center gap-1 mt-1">
                                                    <span class="text-[10px] uppercase font-semibold text-gray-400 w-8">NIP</span>
                                                    <span class="font-mono text-xs text-gray-600 bg-gray-100 px-1 rounded">{{ $emp->employeeDetail->nip ?? '-' }}</span>
                                                </div>
                                                <div class="flex items-center gap-1 mt-0.5">
                                                    <span class="text-[10px] uppercase font-semibold text-gray-400 w-8">BPS</span>
                                                    <span class="font-mono text-xs text-blue-600 bg-blue-50 px-1 rounded">{{ $emp->employeeDetail->nip_lama ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 align-top">
                                        <p class="text-gray-900 font-semibold text-sm mb-1 leading-snug">{{ $emp->employeeDetail->jabatan ?? '-' }}</p>
                                        @if($emp->employeeDetail->pangkat_golongan ?? false)
                                            <span class="bg-purple-50 text-purple-700 border border-purple-100 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide">
                                                {{ $emp->employeeDetail->pangkat_golongan }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 text-xs italic">Tanpa Pangkat</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 align-top">
                                        @if($masaKerjaText != '-')
                                            <div class="flex flex-col">
                                                <span class="font-bold text-gray-800 text-sm">{{ $masaKerjaText }}</span>
                                                <span class="text-[10px] text-gray-400 mt-0.5">TMT: {{ $masaKerjaDetail }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 align-top text-center">
                                        <button @click="userModal = {{ json_encode([
                                            'name' => $emp->name,
                                            'email' => $emp->email,
                                            'nip' => $emp->employeeDetail->nip ?? '-',
                                            'nip_lama' => $emp->employeeDetail->nip_lama ?? '-',
                                            'nik' => $emp->employeeDetail->nik ?? '-',
                                            'jabatan' => $emp->employeeDetail->jabatan ?? '-',
                                            'pangkat' => $emp->employeeDetail->pangkat_golongan ?? '-',
                                            'masa_kerja' => $masaKerjaText,
                                            'tmt' => $masaKerjaDetail,
                                            'pendidikan' => ($emp->employeeDetail->pendidikan_strata ?? '-') . ' ' . ($emp->employeeDetail->pendidikan_jurusan ?? ''),
                                            'lahir' => ($emp->employeeDetail->tempat_lahir ?? '-') . ', ' . ($emp->employeeDetail->tanggal_lahir ?? '-'),
                                            'alamat' => $emp->employeeDetail->alamat_tinggal ?? '-',
                                            'bank_name' => $emp->employeeDetail->bank_name ?? '-',
                                            'rekening' => $emp->employeeDetail->nomor_rekening ?? '-',
                                            'tim' => $emp->teams->pluck('name')->join(', '),
                                        ]) }}" 
                                        class="text-blue-600 hover:text-blue-800 font-medium text-xs border border-blue-200 hover:bg-blue-50 px-4 py-2 rounded-lg transition shadow-sm hover:shadow-md">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">Data tidak ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                        {{ $employees->withQueryString()->links() }}
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-xl">🎂</span> Ultah Bulan Ini
                    </h4>
                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2 scrollbar-hide">
                        @forelse($birthdayUsers as $bday)
                            <div class="flex items-center gap-3 p-3 bg-pink-50/50 rounded-lg border border-pink-100">
                                <div class="h-8 w-8 rounded-full bg-pink-200 text-pink-600 flex items-center justify-center font-bold text-xs">
                                    {{ substr($bday->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 line-clamp-1">{{ $bday->name }}</p>
                                    <p class="text-xs text-pink-600">
                                        {{ \Carbon\Carbon::parse($bday->employeeDetail->tanggal_lahir)->isoFormat('D MMMM') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 text-center py-4">Tidak ada yang ulang tahun bulan ini.</p>
                        @endforelse
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
            type: 'doughnut',
            data: {
                labels: Object.keys(pendidikanData),
                datasets: [{
                    data: Object.values(pendidikanData),
                    backgroundColor: ['#10b981', '#f59e0b', '#6366f1', '#ec4899', '#8b5cf6'],
                    borderWidth: 0,
                    cutout: '60%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // 4. Chart Umur
        new Chart(document.getElementById('chartUmur'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(umurData),
                datasets: [{
                    data: Object.values(umurData),
                    backgroundColor: ['#3b82f6', '#8b5cf6', '#f43f5e', '#64748b'],
                    borderWidth: 0,
                    cutout: '60%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    </script>
</body>
</html>