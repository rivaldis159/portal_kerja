<x-layout>
    <x-slot:title>Detail Mitra - {{ $mitra->nama }}</x-slot:title>

    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('sipetrik.mitras.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center gap-1 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Detail Mitra</h1>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $mitra->nama }}</h2>
                        <p class="text-sm text-gray-500">ID Sobat: {{ $mitra->sobat_id ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Data Pribadi -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Data Pribadi</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-xs text-gray-500">Nik</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $mitra->nik ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Tempat, Tanggal Lahir</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $mitra->tempat_lahir ?? '-' }}, {{ $mitra->tanggal_lahir ? $mitra->tanggal_lahir : '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Jenis Kelamin</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $mitra->jenis_kelamin == 'L' ? 'Laki-laki' : ($mitra->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                            </dd>
                        </div>
                         <div>
                            <dt class="text-xs text-gray-500">Pendidikan Terakhir</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $mitra->pendidikan ?? '-' }}</dd>
                        </div>
                         <div>
                            <dt class="text-xs text-gray-500">Pekerjaan</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $mitra->pekerjaan ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Kontak & Alamat -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Kontak & Alamat</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-xs text-gray-500">Email</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $mitra->email ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">No. Telepon/WA</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $mitra->no_telp ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Provinsi</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $mitra->alamat_prov ?? '-' }} ({{ $mitra->alamat_kab ?? '-' }})</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Kecamatan</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $mitra->district->name ?? $mitra->alamat_kec }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Desa/Kelurahan</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $mitra->village->name ?? $mitra->alamat_desa }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Alamat Lengkap</dt>
                            <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $mitra->alamat_detail ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Posisi/Status (Optional, still in DB but maybe useful here) -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-100 dark:border-gray-600">
                <dl class="grid grid-cols-2 gap-4">
                     <div>
                        <dt class="text-xs text-gray-500">Posisi</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $mitra->posisi ?? '-' }}</dd>
                    </div>
                     <div>
                        <dt class="text-xs text-gray-500">Status Seleksi</dt>
                        <dd class="text-sm font-medium text-gray-900 dark:text-white">{{ $mitra->status_seleksi ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-layout>
