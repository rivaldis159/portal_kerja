<x-layout>
    <x-slot:title>Form Penilaian Kinerja</x-slot:title>

    <div class="max-w-3xl mx-auto p-6">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Evaluasi Kinerja Mitra</h1>
            <p class="text-gray-500 dark:text-gray-400">Berikan penilaian objektif untuk kinerja mitra di bawah ini.</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
            <!-- Header Contract Info -->
            <div class="bg-gray-50 dark:bg-gray-700/50 px-8 py-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 flex items-center justify-center font-bold text-lg">
                        {{ substr($contract->mitra->nama, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $contract->mitra->nama }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $contract->kegiatan }} • {{ $contract->team->name }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('sipetrik.penilaian.store') }}" method="POST" class="p-8">
                @csrf
                <input type="hidden" name="contract_id" value="{{ $contract->id }}">

                <div class="space-y-8">
                    @foreach([
                        'q1_kualitas' => ['label' => 'Kualitas Pekerjaan', 'desc' => 'Hasil pekerjaan sesuai dengan SOP dan standar yang ditetapkan.'], 
                        'q2_inisiatif' => ['label' => 'Inisiatif & Motivasi', 'desc' => 'Kemampuan menyelesaikan masalah dan semangat dalam bekerja.'],
                        'q3_kerjasama' => ['label' => 'Kerjasama Tim', 'desc' => 'Kemampuan berkomunikasi dan berkolaborasi dengan tim.'],
                        'q4_integritas' => ['label' => 'Integritas', 'desc' => 'Kejujuran, disiplin, dan kepatuhan terhadap aturan.'],
                        'q5_keandalan' => ['label' => 'Keandalan', 'desc' => 'Ketepatan waktu penyelesaian dan konsistensi kinerja.']
                    ] as $key => $item)
                    <div x-data="{ rating: 0, hover: 0 }">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                            <div>
                                <label class="block text-base font-bold text-gray-800 dark:text-gray-200">{{ $item['label'] }}</label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $item['desc'] }}</p>
                            </div>
                            <div class="flex items-center gap-1 mt-2 sm:mt-0">
                                <input type="hidden" name="{{ $key }}" :value="rating" required>
                                <template x-for="i in 5">
                                    <button type="button" 
                                            @click="rating = i" 
                                            @mouseenter="hover = i" 
                                            @mouseleave="hover = 0"
                                            class="focus:outline-none transition-transform hover:scale-110">
                                        <svg class="w-8 h-8" :class="(hover >= i || rating >= i) ? 'text-yellow-400 fill-current' : 'text-gray-300 dark:text-gray-600 fill-current'"
                                             viewBox="0 0 24 24" stroke="none">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <div class="text-right h-4">
                            <span x-show="rating > 0" class="text-xs font-medium text-orange-500" x-text="['Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'][rating - 1]"></span>
                        </div>
                    </div>
                    @endforeach

                    <hr class="border-gray-100 dark:border-gray-700">

                    <div class="bg-orange-50 dark:bg-orange-900/10 rounded-xl p-4 border border-orange-100 dark:border-orange-800/30">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="rekomendasi" value="1" checked class="mt-1 w-5 h-5 text-orange-500 rounded focus:ring-orange-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            <div>
                                <span class="font-bold text-gray-800 dark:text-gray-200 block">Rekomendasi</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Apakah Anda merekomendasikan mitra ini untuk kegiatan selanjutnya?</span>
                            </div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">Catatan Tambahan (Opsional)</label>
                        <textarea name="alasan_rekomendasi" rows="3" class="w-full border-gray-300 dark:border-gray-600 rounded-xl p-3 dark:bg-gray-700 dark:text-white focus:ring-orange-500 focus:border-orange-500 shadow-sm" placeholder="Berikan catatan khusus mengenai kinerja mitra..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('sipetrik.penilaian.index') }}" class="px-6 py-2.5 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-xl font-medium transition">Batal</a>
                    <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl hover:from-orange-600 hover:to-orange-700 font-bold transition shadow-lg shadow-orange-500/30 transform hover:-translate-y-0.5">
                        Kirim Penilaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
