<x-layout>
    <x-slot:title>Detail Penawaran Kerja</x-slot:title>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Penawaran Kerja</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Status: 
                    <span class="px-2 py-1 text-xs rounded-full font-medium
                        {{ $contract->status == 'offered' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $contract->status == 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $contract->status == 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $contract->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($contract->status) }}
                    </span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('sipetrik.penawaran-kerja.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm">
                    Kembali
                </a>
                
                @if($contract->status != 'completed' && $contract->status != 'cancelled')
                <a href="{{ route('sipetrik.penawaran-kerja.edit', $contract->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 shadow-sm">
                    Edit
                </a>
                @endif

                <form action="{{ route('sipetrik.penawaran-kerja.destroy', $contract->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kontrak ini? Data penilaian juga akan terhapus.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-sm font-medium text-red-600 hover:bg-red-100 dark:hover:bg-red-900/40 shadow-sm">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Activity Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100">Informasi Pekerjaan</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</label>
                            <div class="mt-1 text-lg font-medium text-gray-900 dark:text-white">{{ $contract->kegiatan }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian Tugas</label>
                            <div class="mt-1 text-gray-700 dark:text-gray-300">{{ $contract->uraian_tugas ?? '-' }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Mulai</label>
                                <div class="mt-1 text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($contract->tanggal_mulai)->format('d F Y') }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Selesai</label>
                                <div class="mt-1 text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($contract->tanggal_selesai)->format('d F Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mitra & Team Info -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100">Pihak Terkait</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Mitra Statistik</label>
                            <div class="mt-2 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                    {{ substr($contract->mitra->nama, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $contract->mitra->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $contract->mitra->sobat_id ?? 'No ID' }}</div>
                                </div>
                            </div>
                            <!-- Link to Mitra Detail if possible -->
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Tim Kerja</label>
                            <div class="mt-2 text-gray-900 dark:text-white">
                                <span class="px-2 py-1 rounded text-sm border" style="background-color: {{ $contract->team->color }}10; color: {{ $contract->team->color }}; border-color: {{ $contract->team->color }}30;">
                                    {{ $contract->team->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar: Value & Actions -->
            <div class="space-y-6">
                <!-- Contract Value -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Nilai Kontrak</label>
                        <div class="text-3xl font-bold text-orange-600 dark:text-orange-400">
                            Rp{{ number_format($contract->nilai_kontrak, 0, ',', '.') }}
                        </div>
                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400 flex justify-between border-t border-gray-100 dark:border-gray-700 pt-2">
                            <span>Volume: {{ $contract->volume }} {{ $contract->satuan }}</span>
                            <span>@ Rp{{ number_format($contract->harga_satuan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden p-6">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Aksi Lanjutan</h3>
                    
                    @if($contract->status == 'offered')
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg mb-4">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200 mb-3">Kontrak saat ini masih berupa penawaran. Jika mitra setuju, tandai sebagai diterima.</p>
                            <form action="{{ route('sipetrik.penawaran-kerja.update-status', $contract->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="accepted">
                                <button type="submit" class="w-full py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-sm font-medium">
                                    Tandai Diterima (Sepakat)
                                </button>
                            </form>
                        </div>
                    @endif

                    @if($contract->status == 'accepted')
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-4">
                            <p class="text-sm text-blue-800 dark:text-blue-200 mb-3">Pekerjaan sedang berlangsung. Jika sudah selesai, lanjutkan ke penilaian kinerja.</p>
                            <a href="{{ route('sipetrik.penilaian.create', ['contract_id' => $contract->id]) }}" class="block w-full text-center py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm font-medium">
                                Selesaikan & Nilai Kinerja
                            </a>
                        </div>
                    @endif

                    @if($contract->status == 'completed')
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg mb-4">
                            <p class="text-sm text-green-800 dark:text-green-200 mb-3">Kontrak selesai.</p>
                            @if($contract->penilaian)
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-yellow-500 mb-1">
                                        {{ number_format($contract->penilaian->average_score, 1) }} <span class="text-base text-gray-400">/ 5.0</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Skor Penilaian</p>
                                </div>
                            @else
                                <a href="{{ route('sipetrik.penilaian.create', ['contract_id' => $contract->id]) }}" class="block w-full text-center py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm font-medium">
                                    Input Penilaian
                                </a>
                            @endif
                        </div>
                    @endif

                    @if($contract->status != 'cancelled')
                    <hr class="my-4 border-gray-100 dark:border-gray-700">
                    <form action="{{ route('sipetrik.penawaran-kerja.update-status', $contract->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="w-full py-2 border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm">
                            Batalkan Kontrak
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
