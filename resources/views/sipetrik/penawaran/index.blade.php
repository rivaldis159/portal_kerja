<x-layout>
    <x-slot:title>Daftar Penawaran Kerja</x-slot:title>

    <div class="p-6" x-data="{ 
        detailOpen: false, 
        detailContent: 'Loading...', 
        openDetail(url) {
            this.detailOpen = true;
            this.detailContent = '<div class=\'p-8 text-center\'><svg class=\'animate-spin h-8 w-8 mx-auto text-orange-500\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z\'></path></svg><p class=\'mt-2 text-gray-500\'>Memuat data...</p></div>';
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                this.detailContent = html;
            })
            .catch(error => {
                this.detailContent = '<p class=\'text-red-500 text-center p-4\'>Gagal memuat data.</p>';
                console.error('Error:', error);
            });
        }
    }">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Daftar Kontrak Kerja</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola kontrak dan penilaian kinerja mitra.</p>
            </div>
            <a href="{{ route('sipetrik.penawaran-kerja.create') }}" class="px-4 py-2 bg-orange-600 text-white rounded-xl hover:bg-orange-700 transition shadow-lg shadow-orange-500/30 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Buat Penawaran Baru
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold text-xs border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4">Kegiatan</th>
                            <th class="px-6 py-4">Mitra & Tim</th>
                            <th class="px-6 py-4">Nilai Kontrak</th>
                            <th class="px-6 py-4">Periode</th>
                            <th class="px-6 py-4 text-center">Penilaian</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($contracts as $contract)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 align-top">
                                <div class="font-semibold text-gray-900 dark:text-white mb-1">{{ $contract->kegiatan }}</div>
                                <div class="text-xs text-gray-500 line-clamp-2" title="{{ $contract->uraian_tugas }}">{{ $contract->uraian_tugas ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 text-xs flex items-center justify-center font-bold">
                                        {{ substr($contract->mitra->nama, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $contract->mitra->nama }}</span>
                                </div>
                                <span class="px-2 py-0.5 text-xs rounded border inline-block" style="background-color: {{ $contract->team->color }}10; color: {{ $contract->team->color }}; border-color: {{ $contract->team->color }}30;">
                                    {{ $contract->team->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="font-bold text-gray-900 dark:text-white">Rp{{ number_format($contract->nilai_kontrak, 0, ',', '.') }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $contract->volume }} {{ $contract->satuan }}</div>
                            </td>
                            <td class="px-6 py-4 align-top whitespace-nowrap text-gray-600 dark:text-gray-400 text-xs">
                                <div><span class="font-medium">Mulai:</span> {{ \Carbon\Carbon::parse($contract->tanggal_mulai)->format('d/m/Y') }}</div>
                                <div class="mt-1"><span class="font-medium">Selesai:</span> {{ \Carbon\Carbon::parse($contract->tanggal_selesai)->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 align-top text-center">
                                @if($contract->penilaian)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                        ★ {{ number_format($contract->penilaian->average_score, 1) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum dinilai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top text-right">
                                <button @click="openDetail('{{ route('sipetrik.penawaran-kerja.show', $contract->id) }}')" 
                                        class="text-orange-600 hover:text-orange-900 font-medium text-sm bg-orange-50 dark:bg-orange-900/20 px-3 py-1.5 rounded-lg border border-orange-200 dark:border-orange-800 hover:bg-orange-100 transition">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-lg font-medium text-gray-900 dark:text-white">Belum ada penawaran kerja</p>
                                <p class="text-sm mt-1">Mulai dengan membuat penawaran baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                {{ $contracts->links() }}
            </div>
        </div>

        <!-- Modal -->
        <div x-show="detailOpen" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            
            <!-- Backdrop -->
            <div x-show="detailOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 @click="detailOpen = false"></div>

            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="detailOpen" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div x-html="detailContent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
