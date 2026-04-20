<x-layout>
    <x-slot:title>Daftar Penilaian Kinerja</x-slot:title>

    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Penilaian Kinerja</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar penilaian kinerja mitra per tim dan kegiatan.</p>
            </div>
        </div>

        <div class="space-y-8">
            @php
                $groupedContracts = $contracts->groupBy(['team.name', 'kegiatan']);
            @endphp

            @forelse($groupedContracts as $teamName => $activities)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
                        <div class="w-1 h-6 bg-orange-500 rounded-full"></div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $teamName }}</h2>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($activities as $activityName => $items)
                            <div class="p-6">
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">
                                    {{ $activityName }}
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($items as $contract)
                                    <div class="flex items-center p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-orange-300 dark:hover:border-orange-700 hover:bg-orange-50 dark:hover:bg-gray-700/50 transition group bg-white dark:bg-gray-800">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 flex items-center justify-center font-bold text-sm shrink-0">
                                            {{ substr($contract->mitra->nama, 0, 1) }}
                                        </div>
                                        <div class="ml-4 flex-1 min-w-0">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $contract->mitra->nama }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $contract->mitra->sobat_id ?? 'No ID' }}</p>
                                        </div>
                                        <div class="ml-4 text-right">
                                            @if($contract->penilaian)
                                                <div class="flex flex-col items-end">
                                                    <span class="text-lg font-bold text-yellow-500 flex items-center gap-1">
                                                        ★ {{ number_format($contract->penilaian->average_score, 1) }}
                                                    </span>
                                                    <span class="text-[10px] text-gray-400">Selesai</span>
                                                </div>
                                            @else
                                                <a href="{{ route('sipetrik.penilaian.create', ['contract_id' => $contract->id]) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-full hover:bg-orange-200 transition">
                                                    Nilai
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Belum ada data untuk dinilai.</p>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $contracts->links() }}
            </div>
        </div>
    </div>
</x-layout>
