<table class="w-full text-left text-sm text-gray-600">
    <thead class="bg-gray-50/50 text-gray-500 font-medium border-b border-gray-200 uppercase tracking-wider text-xs">
        <tr>
            <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition group select-none" @click="setSort('name')">
                <div class="flex items-center gap-1">
                    Nama Pegawai
                    <span x-show="sort === 'name'" :class="{'text-primary-600': true}" x-text="direction === 'asc' ? '▲' : '▼'"></span>
                    <span x-show="sort !== 'name'" class="text-gray-300 opacity-0 group-hover:opacity-100 transition">⇅</span>
                </div>
            </th>
            
            <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition group select-none" @click="setSort('jabatan')">
                <div class="flex items-center gap-1">
                    Jabatan & Pangkat
                    <span x-show="sort === 'jabatan'" :class="{'text-primary-600': true}" x-text="direction === 'asc' ? '▲' : '▼'"></span>
                    <span x-show="sort !== 'jabatan'" class="text-gray-300 opacity-0 group-hover:opacity-100 transition">⇅</span>
                </div>
            </th>

            <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition group select-none" @click="setSort('masa_kerja')">
                <div class="flex items-center gap-1">
                    Masa Kerja
                    <span x-show="sort === 'masa_kerja'" :class="{'text-primary-600': true}" x-text="direction === 'asc' ? '▲' : '▼'"></span>
                    <span x-show="sort !== 'masa_kerja'" class="text-gray-300 opacity-0 group-hover:opacity-100 transition">⇅</span>
                </div>
            </th>

            <th class="px-6 py-4 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
        @forelse($employees as $employee)
        <tr class="hover:bg-gray-50/50 transition">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                        {{ substr($employee->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-gray-900">{{ $employee->name }}</div>
                        <div class="flex flex-col text-xs font-mono space-y-1 mt-1">
                            @if($employee->employeeDetail && $employee->employeeDetail->nip)
                            <span class="bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded w-max">NIP: {{ $employee->employeeDetail->nip }}</span>
                            @endif
                            @if($employee->employeeDetail && $employee->employeeDetail->nip_lama)
                            <span class="bg-gray-50 text-gray-500 px-1.5 py-0.5 rounded w-max">NIP Lama: {{ $employee->employeeDetail->nip_lama }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="font-medium text-gray-800">{{ $employee->employeeDetail->jabatan ?? '-' }}</div>
                <div class="text-xs text-blue-600 bg-blue-50 px-2 py-0.5 rounded w-max mt-1">
                    {{ $employee->employeeDetail->pangkat_golongan ?? '-' }}
                </div>
            </td>
            <td class="px-6 py-4">
                @php
                    $masaKerja = '-';
                    if($employee->employeeDetail && $employee->employeeDetail->nip) {
                       $nip = $employee->employeeDetail->nip;
                       if (strlen($nip) == 18) {
                           if (str_contains($employee->employeeDetail->pangkat_golongan ?? '', 'PPPK')) {
                               $startYear = (int)substr($nip, 8, 4);
                               $masaKerja = max(0, (int)date('Y') - $startYear) . ' Tahun';
                           } else {
                               $tmtStr = substr($nip, 8, 6);
                               try {
                                   $tmt = \Carbon\Carbon::createFromFormat('Ym', $tmtStr);
                                   $diff = $tmt->diff(now());
                                   $masaKerja = $diff->y . ' Tahun ' . $diff->m . ' Bulan';
                               } catch(\Exception $e) {}
                           }
                       }
                    }
                @endphp
                <span class="font-bold text-gray-700 text-sm">{{ $masaKerja }}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <button @click="userModal = {
                    name: '{{ addslashes($employee->name) }}',
                    email: '{{ addslashes($employee->email) }}',
                    jabatan: '{{ addslashes($employee->employeeDetail->jabatan ?? '-') }}',
                    pangkat: '{{ addslashes($employee->employeeDetail->pangkat_golongan ?? '-') }}',
                    nip: '{{ $employee->employeeDetail->nip ?? '-' }}',
                    nip_lama: '{{ $employee->employeeDetail->nip_lama ?? '-' }}',
                    tmt: '{{ $employee->employeeDetail->tmt_pangkat ? \Carbon\Carbon::parse($employee->employeeDetail->tmt_pangkat)->isoFormat('D MMMM Y') : '-' }}',
                    masa_kerja: '{{ $masaKerja }}',
                    pendidikan: '{{ addslashes(($employee->employeeDetail->pendidikan_strata ?? '-') . ' ' . ($employee->employeeDetail->pendidikan_jurusan ?? '')) }}',
                    nik: '{{ $employee->employeeDetail->nik ?? '-' }}',
                    lahir: '{{ ($employee->employeeDetail->tempat_lahir ?? '-') . ', ' . ($employee->employeeDetail->tanggal_lahir ? \Carbon\Carbon::parse($employee->employeeDetail->tanggal_lahir)->isoFormat('D MMMM Y') : '-') }}',
                    alamat: '{{ addslashes($employee->employeeDetail->alamat_tinggal ?? '-') }}',
                    bank_name: '{{ addslashes($employee->employeeDetail->bank_name ?? '-') }}',
                    rekening: '{{ $employee->employeeDetail->nomor_rekening ?? '-' }}'
                }" class="px-3 py-1.5 rounded-lg border border-gray-300 text-gray-700 text-xs font-bold hover:bg-gray-50 hover:text-primary-600 hover:border-primary-300 transition shadow-sm bg-white">
                    Detail
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">
                Tidak ada data pegawai ditemukan.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
    <div class="text-xs text-gray-500">
        Menampilkan <span class="font-bold">{{ $employees instanceof \Illuminate\Pagination\LengthAwarePaginator ? $employees->firstItem() : 1 }}</span> 
        s.d <span class="font-bold">{{ $employees instanceof \Illuminate\Pagination\LengthAwarePaginator ? $employees->lastItem() : $employees->count() }}</span> 
        dari <span class="font-bold">{{ $employees instanceof \Illuminate\Pagination\LengthAwarePaginator ? $employees->total() : $employees->count() }}</span> data.
    </div>
    
    <div class="flex items-center gap-2" id="pagination-links-container">
        @if($employees instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $employees->links('pagination::tailwind') }}
        @endif
    </div>
</div>
