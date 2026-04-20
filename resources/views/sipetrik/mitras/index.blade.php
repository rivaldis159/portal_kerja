<x-layout>
    <x-slot:title>Data Mitra 2026</x-slot:title>

    <div class="p-6" x-data="{ mitraModal: null }">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Data Mitra Statistik</h1>
            <div class="flex gap-2">

                <!-- Import Button Trigger -->
                <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Import Excel
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Filter Data</h3>
            </div>
            <div class="p-4">
                <form action="{{ route('sipetrik.mitras.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="district_id" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Kecamatan</label>
                        <select name="district_id" id="district_id" onchange="this.form.submit()" class="w-full bg-gray-50 border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition shadow-sm">
                            <option value="">Semua Kecamatan</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="village_id" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Desa/Kelurahan</label>
                        <select name="village_id" id="village_id" onchange="this.form.submit()" class="w-full bg-gray-50 border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition shadow-sm" {{ $villages->isEmpty() ? 'disabled' : '' }}>
                            <option value="">Semua Desa</option>
                            @foreach($villages as $village)
                                <option value="{{ $village->id }}" {{ request('village_id') == $village->id ? 'selected' : '' }}>
                                    {{ $village->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="search" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wide">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / ID Sobat..." class="w-full bg-gray-50 border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition shadow-sm">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition focus:ring-4 focus:ring-orange-300 shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari
                        </button>
                        @if(request()->anyFilled(['district_id', 'village_id', 'search']))
                            <a href="{{ route('sipetrik.mitras.index') }}" class="px-4 py-2.5 bg-white border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 hover:text-gray-800 transition shadow-sm" title="Reset Filter">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50/50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-200 uppercase tracking-wider text-xs font-semibold border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4">
                                <a href="{{ route('sipetrik.mitras.index', array_merge(request()->query(), ['sort' => 'nama', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 cursor-pointer hover:text-orange-500 transition">
                                    <span>Nama Mitra</span>
                                    <svg class="w-3 h-3 text-gray-400 group-hover:text-orange-500 {{ request('sort') === 'nama' ? (request('direction') === 'asc' ? 'rotate-180' : '') : 'opacity-0 group-hover:opacity-50' }} transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </a>
                            </th>
                            <th class="px-6 py-4">
                                <a href="{{ route('sipetrik.mitras.index', array_merge(request()->query(), ['sort' => 'district_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 cursor-pointer hover:text-orange-500 transition">
                                    <span>Kecamatan</span>
                                    <svg class="w-3 h-3 text-gray-400 group-hover:text-orange-500 {{ request('sort') === 'district_name' ? (request('direction') === 'asc' ? 'rotate-180' : '') : 'opacity-0 group-hover:opacity-50' }} transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </a>
                            </th>
                             <th class="px-6 py-4">
                                <a href="{{ route('sipetrik.mitras.index', array_merge(request()->query(), ['sort' => 'village_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="group flex items-center gap-1 cursor-pointer hover:text-orange-500 transition">
                                    <span>Desa</span>
                                    <svg class="w-3 h-3 text-gray-400 group-hover:text-orange-500 {{ request('sort') === 'village_name' ? (request('direction') === 'asc' ? 'rotate-180' : '') : 'opacity-0 group-hover:opacity-50' }} transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </a>
                            </th>
                            <th class="px-6 py-4">Kontak</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($mitras as $mitra)
                        <tr class="hover:bg-orange-50/50 dark:hover:bg-gray-700/50 transition duration-150 group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-100 to-amber-100 text-orange-600 flex items-center justify-center font-bold text-xs shadow-sm">
                                        {{ substr($mitra->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white group-hover:text-orange-600 transition">{{ $mitra->nama }}</div>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            @if($mitra->sobat_id)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-green-100 text-green-700 border border-green-200">
                                                    ID: {{ $mitra->sobat_id }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-500 border border-gray-200">
                                                    No ID
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $mitra->district->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $mitra->village->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ $mitra->no_telp ?: '-' }}
                                    </div>
                                    @if($mitra->email)
                                    <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        <span>{{ $mitra->email }}</span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button @click="mitraModal = {
                                    nama: '{{ addslashes($mitra->nama) }}',
                                    sobat_id: '{{ addslashes($mitra->sobat_id ?? '-') }}',
                                    email: '{{ addslashes($mitra->email ?? '-') }}',
                                    no_telp: '{{ addslashes($mitra->no_telp ?? '-') }}',
                                    district: '{{ addslashes($mitra->district->name ?? '-') }}',
                                    village: '{{ addslashes($mitra->village->name ?? '-') }}',
                                    alamat_detail: '{{ addslashes($mitra->alamat_detail ?? '-') }}',
                                    tempat_lahir: '{{ addslashes($mitra->tempat_lahir ?? '-') }}',
                                    tanggal_lahir: '{{ $mitra->tanggal_lahir ?? '-' }}',
                                    jenis_kelamin: '{{ $mitra->jenis_kelamin ?? '-' }}',
                                    pendidikan: '{{ addslashes($mitra->pendidikan ?? '-') }}',
                                    pekerjaan: '{{ addslashes($mitra->pekerjaan ?? '-') }}'
                                }" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 bg-gray-50/50">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <span>Belum ada data mitra yang sesuai filter.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $mitras->links() }}
            </div>
        </div>
        
        <!-- Mitra Modal -->
        <div x-show="mitraModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition>
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="mitraModal = null"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all">
                    <div class="bg-gray-800 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                             <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Detail Mitra
                        </h3>
                        <button @click="mitraModal = null" class="text-gray-400 hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 bg-gray-50/50 max-h-[75vh] overflow-y-auto">
                        <div class="flex items-center gap-5 mb-8 pb-6 border-b border-gray-200">
                            <div class="h-20 w-20 rounded-full bg-orange-500 text-white flex items-center justify-center text-3xl font-bold shadow-lg ring-4 ring-orange-50">
                                <span x-text="mitraModal?.nama.charAt(0)"></span>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900" x-text="mitraModal?.nama"></h2>
                                <p class="text-sm text-gray-500 mb-2">ID Sobat: <span class="font-mono" x-text="mitraModal?.sobat_id"></span></p>
                                <div class="flex flex-wrap gap-2">
                                     <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-200 text-gray-700" x-text="mitraModal?.pekerjaan || 'Pekerjaan: -'"></span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="space-y-4">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b pb-1">Identitas</h4>
                                <div class="grid grid-cols-3 gap-1">
                                    <span class="text-xs text-gray-500">TTL</span>
                                    <span class="col-span-2 text-sm font-medium text-gray-800" x-text="(mitraModal?.tempat_lahir || '-') + ', ' + (mitraModal?.tanggal_lahir || '-')"></span>
                                </div>
                                <div class="grid grid-cols-3 gap-1">
                                    <span class="text-xs text-gray-500">Jenis Kelamin</span>
                                    <span class="col-span-2 text-sm font-medium text-gray-800" x-text="mitraModal?.jenis_kelamin === 'L' ? 'Laki-laki' : (mitraModal?.jenis_kelamin === 'P' ? 'Perempuan' : '-')"></span>
                                </div>
                                <div class="grid grid-cols-3 gap-1">
                                    <span class="text-xs text-gray-500">Pendidikan</span>
                                    <span class="col-span-2 text-sm font-medium text-gray-800" x-text="mitraModal?.pendidikan || '-'"></span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b pb-1">Kontak & Alamat</h4>
                                <div class="mb-2">
                                    <span class="block text-xs text-gray-500 mb-0.5">Email</span>
                                    <span class="text-sm font-medium text-gray-800" x-text="mitraModal?.email || '-'"></span>
                                </div>
                                <div class="mb-2">
                                    <span class="block text-xs text-gray-500 mb-0.5">No. Telp / WA</span>
                                    <span class="text-sm font-medium text-gray-800" x-text="mitraModal?.no_telp || '-'"></span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-500 mb-0.5">Alamat</span>
                                    <span class="text-sm font-medium text-gray-800 leading-snug">
                                        <span x-text="mitraModal?.alamat_detail || '-'"></span><br>
                                        <span class="text-xs text-gray-500">
                                            Kec. <span x-text="mitraModal?.district"></span>, 
                                            Desa <span x-text="mitraModal?.village"></span>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-100 px-6 py-4 flex justify-end">
                        <button @click="mitraModal = null" class="bg-white border border-gray-300 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Import Modal (Simple) -->
    <div id="importModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md shadow-xl">
            <h2 class="text-lg font-bold mb-4 dark:text-white">Import Data Mitra</h2>
            <form action="{{ route('sipetrik.mitras.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1 dark:text-gray-300">File Excel (.xlsx)</label>
                    <input type="file" name="file" required class="w-full border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 hover:text-gray-800 dark:text-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Upload</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
