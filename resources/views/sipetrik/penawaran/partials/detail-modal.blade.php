<div class="space-y-6">
    <!-- Header Modal -->
    <div class="flex justify-between items-start">
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $contract->kegiatan }}</h3>
            <p class="text-sm text-gray-500">{{ $contract->mitra->nama }} - {{ $contract->team->name }}</p>
        </div>
        <button @click="open = false" class="text-gray-400 hover:text-gray-500">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Details -->
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase">Uraian Tugas</label>
                <div class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $contract->uraian_tugas ?? '-' }}</div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase">Mulai</label>
                    <div class="mt-1 text-sm font-medium">{{ \Carbon\Carbon::parse($contract->tanggal_mulai)->format('d M Y') }}</div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase">Selesai</label>
                    <div class="mt-1 text-sm font-medium">{{ \Carbon\Carbon::parse($contract->tanggal_selesai)->format('d M Y') }}</div>
                </div>
            </div>

            <div>
                 <label class="block text-xs font-medium text-gray-500 uppercase">Volume & Harga</label>
                 <div class="mt-1 text-sm">
                     {{ $contract->volume }} {{ $contract->satuan }} x Rp{{ number_format($contract->harga_satuan, 0, ',', '.') }}
                 </div>
                 <div class="text-lg font-bold text-orange-600 mt-1">
                     Total: Rp{{ number_format($contract->nilai_kontrak, 0, ',', '.') }}
                 </div>
            </div>
        </div>

        <!-- Meta & Actions -->
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 space-y-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-2">Penilaian Kinerja</label>
                @if($contract->penilaian)
                    <div class="flex items-center gap-2">
                        <div class="text-2xl font-bold text-yellow-500">{{ number_format($contract->penilaian->average_score, 1) }}</div>
                        <div class="text-xs text-gray-500">
                            (Dinilai pada {{ \Carbon\Carbon::parse($contract->penilaian->created_at)->format('d M Y') }})
                        </div>
                    </div>
                @else
                    <a href="{{ route('sipetrik.penilaian.create', ['contract_id' => $contract->id]) }}" 
                       class="block w-full text-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        Input Penilaian
                    </a>
                @endif
            </div>

            <div class="pt-4 border-t border-gray-200 dark:border-gray-600 flex flex-col gap-2">
                <a href="{{ route('sipetrik.penawaran-kerja.edit', $contract->id) }}" 
                   class="block w-full text-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                    Edit Kontrak
                </a>
                
                <form action="{{ route('sipetrik.penawaran-kerja.destroy', $contract->id) }}" method="POST" onsubmit="return confirm('Hapus kontrak ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-50 text-red-600 border border-red-200 text-sm font-medium rounded-lg hover:bg-red-100 transition">
                        Hapus Kontrak
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
