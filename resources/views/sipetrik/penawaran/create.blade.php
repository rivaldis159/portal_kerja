<x-layout>
    <x-slot:title>Buat Penawaran Kerja</x-slot:title>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Penawaran Kerja</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Buat kontrak kerja baru untuk mitra statistik.</p>
            </div>
            <a href="{{ route('sipetrik.penawaran-kerja.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <form action="{{ route('sipetrik.penawaran-kerja.store') }}" method="POST"
              x-data="{ 
                  volume: 0, 
                  harga: 0, 
                  total: 0, 
                  calculate() { this.total = this.volume * this.harga; } 
              }"
              class="space-y-6">
            @csrf

            <!-- Section 1: Detail Pihak -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 relative z-30">
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3 rounded-t-xl">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Detail Pihak</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Team Select -->
                    <div x-data='searchableSelect({ 
                            options: {!! $teams->map(fn($t) => ["value" => $t->id, "label" => $t->name])->values()->toJson(JSON_HEX_APOS) !!},
                            selected: null,
                            placeholder: "Pilih Tim Kerja..."
                        })' class="relative z-50">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tim Kerja <span class="text-red-500">*</span></label>
                        <input type="hidden" name="team_id" x-model="selected" required>
                        
                        <div class="relative" @click.away="open = false">
                            <button type="button" @click="open = !open" 
                                    class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg pl-3 pr-10 py-2.5 text-left cursor-default focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm shadow-sm transition">
                                <span class="block truncate" x-text="selectedLabel" :class="{'text-gray-500': !selected}"></span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </span>
                            </button>

                            <div x-show="open" x-transition.opacity.duration.100ms
                                 class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-700 shadow-xl max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                 style="display: none;">
                                <div class="sticky top-0 z-10 bg-white dark:bg-gray-700 px-2 py-1.5 border-b border-gray-100 dark:border-gray-600">
                                    <input type="text" x-model="search" class="w-full border-gray-200 dark:border-gray-600 rounded-md text-sm px-2 py-1.5 focus:ring-orange-500 focus:border-orange-500 bg-gray-50 dark:bg-gray-800 dark:text-white" placeholder="Cari...">
                                </div>
                                <template x-for="option in filteredOptions" :key="option.value">
                                    <div @click="select(option.value)" 
                                         class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-orange-50 dark:hover:bg-gray-600 text-gray-900 dark:text-white"
                                         :class="{ 'bg-orange-50 dark:bg-gray-600': selected === option.value }">
                                        <span class="block truncate" x-text="option.label" :class="{ 'font-semibold': selected === option.value, 'font-normal': selected !== option.value }"></span>
                                        <span x-show="selected === option.value" class="absolute inset-y-0 right-0 flex items-center pr-4 text-orange-600">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </span>
                                    </div>
                                </template>
                                <div x-show="filteredOptions.length === 0" class="cursor-default select-none relative py-2 pl-3 pr-9 text-gray-500 dark:text-gray-400 italic">
                                    Tidak ditemukan
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mitra Select -->
                    <div x-data='searchableSelect({ 
                            options: {!! $mitras->map(fn($m) => ["value" => $m->id, "label" => $m->nama . " (" . ($m->sobat_id ?? "No ID") . ")"])->values()->toJson(JSON_HEX_APOS) !!},
                            selected: null,
                            placeholder: "Pilih Mitra Statistik..."
                        })' class="relative z-40">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mitra Statistik <span class="text-red-500">*</span></label>
                        <input type="hidden" name="mitra_id" x-model="selected" required>
                        
                        <div class="relative" @click.away="open = false">
                            <button type="button" @click="open = !open" 
                                    class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg pl-3 pr-10 py-2.5 text-left cursor-default focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm shadow-sm transition">
                                <span class="block truncate" x-text="selectedLabel" :class="{'text-gray-500': !selected}"></span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </span>
                            </button>

                            <div x-show="open" x-transition.opacity.duration.100ms
                                 class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-700 shadow-xl max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                 style="display: none;">
                                <div class="sticky top-0 z-10 bg-white dark:bg-gray-700 px-2 py-1.5 border-b border-gray-100 dark:border-gray-600">
                                    <input type="text" x-model="search" class="w-full border-gray-200 dark:border-gray-600 rounded-md text-sm px-2 py-1.5 focus:ring-orange-500 focus:border-orange-500 bg-gray-50 dark:bg-gray-800 dark:text-white" placeholder="Cari Mitra...">
                                </div>
                                <template x-for="option in filteredOptions" :key="option.value">
                                    <div @click="select(option.value)" 
                                         class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-orange-50 dark:hover:bg-gray-600 text-gray-900 dark:text-white"
                                         :class="{ 'bg-orange-50 dark:bg-gray-600': selected === option.value }">
                                        <div class="flex flex-col">
                                            <span class="block truncate font-medium" x-text="option.label"></span>
                                        </div>
                                        <span x-show="selected === option.value" class="absolute inset-y-0 right-0 flex items-center pr-4 text-orange-600">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </span>
                                    </div>
                                </template>
                                <div x-show="filteredOptions.length === 0" class="cursor-default select-none relative py-2 pl-3 pr-9 text-gray-500 dark:text-gray-400 italic">
                                    Mitra tidak ditemukan
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1.5 ml-1">Ketik nama atau ID untuk mencari mitra.</p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Detail Pekerjaan (z-index lower) -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden relative z-20">
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Detail Pekerjaan</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div x-data='searchableSelect({ 
                                options: {!! $activities->map(fn($a) => ["value" => $a->id, "label" => $a->name])->values()->toJson(JSON_HEX_APOS) !!},
                                selected: null,
                                placeholder: "Pilih Kegiatan..."
                            })' class="relative z-50">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Kegiatan <span class="text-red-500">*</span></label>
                            <input type="hidden" name="activity_id" x-model="selected" required>
                            <input type="hidden" name="kegiatan" x-bind:value="selected ? options.find(o => o.value == selected)?.label : ''">
                            
                            <div class="relative" @click.away="open = false">
                                <button type="button" @click="open = !open" 
                                        class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg pl-3 pr-10 py-2.5 text-left cursor-default focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm shadow-sm transition">
                                    <span class="block truncate" x-text="selectedLabel" :class="{'text-gray-500': !selected}"></span>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </span>
                                </button>
    
                                <div x-show="open" x-transition.opacity.duration.100ms
                                     class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-700 shadow-xl max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                     style="display: none;">
                                    <div class="sticky top-0 z-10 bg-white dark:bg-gray-700 px-2 py-1.5 border-b border-gray-100 dark:border-gray-600">
                                        <input type="text" x-model="search" class="w-full border-gray-200 dark:border-gray-600 rounded-md text-sm px-2 py-1.5 focus:ring-orange-500 focus:border-orange-500 bg-gray-50 dark:bg-gray-800 dark:text-white" placeholder="Cari Kegiatan...">
                                    </div>
                                    <template x-for="option in filteredOptions" :key="option.value">
                                        <div @click="select(option.value)" 
                                             class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-orange-50 dark:hover:bg-gray-600 text-gray-900 dark:text-white"
                                             :class="{ 'bg-orange-50 dark:bg-gray-600': selected === option.value }">
                                            <span class="block truncate" x-text="option.label" :class="{ 'font-semibold': selected === option.value, 'font-normal': selected !== option.value }"></span>
                                            <span x-show="selected === option.value" class="absolute inset-y-0 right-0 flex items-center pr-4 text-orange-600">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                            </span>
                                        </div>
                                    </template>
                                    <div x-show="filteredOptions.length === 0" class="cursor-default select-none relative py-2 pl-3 pr-9 text-gray-500 dark:text-gray-400 italic">
                                        Kegiatan tidak ditemukan
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1.5 ml-1">Ketik nama untuk mencari kegiatan.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Uraian Tugas</label>
                            <textarea name="uraian_tugas" rows="3" class="w-full border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500 shadow-sm transition" placeholder="Deskripsi singkat tugas yang akan dilakukan..."></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_mulai" required class="w-full border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500 shadow-sm transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_selesai" required class="w-full border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500 shadow-sm transition">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Nilai Kontrak -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden relative z-10">
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">Volume & Nilai Kontrak</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Volume <span class="text-red-500">*</span></label>
                            <input type="number" name="volume" x-model="volume" @input="calculate()" required min="1" class="w-full border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500 shadow-sm transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Satuan <span class="text-red-500">*</span></label>
                            <select name="satuan" required class="w-full border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500 shadow-sm transition">
                                <option value="Dokumen">Dokumen</option>
                                <option value="BS">Blok Sensus</option>
                                <option value="Ruta">Rumah Tangga</option>
                                <option value="Segmen">Segmen</option>
                                <option value="SLS">SLS</option>
                                <option value="O-B">Orang-Bulan</option>
                                <option value="Paket">Paket</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="harga_satuan" x-model="harga" @input="calculate()" required min="0" class="w-full border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white px-4 py-2.5 focus:ring-orange-500 focus:border-orange-500 shadow-sm transition">
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/40 dark:to-orange-900/20 p-6 rounded-xl border border-orange-200 dark:border-orange-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div>
                            <span class="block text-sm text-orange-800 dark:text-orange-300 font-medium">Total Estimasi Nilai Kontrak</span>
                            <span class="text-xs text-orange-600 dark:text-orange-400">Dihitung otomatis (Volume x Harga)</span>
                        </div>
                        <span class="text-3xl font-extrabold text-orange-600 dark:text-orange-400 tracking-tight" x-text="'Rp ' + (total).toLocaleString('id-ID')">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('sipetrik.penawaran-kerja.index') }}" class="px-6 py-2.5 text-gray-700 hover:text-gray-900 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition shadow-sm">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-orange-600 text-white rounded-xl hover:bg-orange-700 font-medium transition shadow-lg shadow-orange-500/30 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Buat Penawaran
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('searchableSelect', ({ options, selected, placeholder }) => ({
                search: '',
                open: false,
                options: options,
                selected: selected,
                placeholder: placeholder,
                
                get filteredOptions() {
                    if (this.search === '') return this.options;
                    return this.options.filter(option => 
                        option.label.toLowerCase().includes(this.search.toLowerCase())
                    );
                },
                
                get selectedLabel() {
                    if (!this.selected) return this.placeholder;
                    const option = this.options.find(o => o.value == this.selected);
                    return option ? option.label : this.placeholder;
                },
                
                select(value) {
                    this.selected = value;
                    this.open = false;
                    this.search = '';
                }
            }))
        })
    </script>
</x-layout>
