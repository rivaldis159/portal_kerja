@forelse($employees as $emp)
    @php
        // HITUNG MASA KERJA
        $masaKerjaText = '-';
        $masaKerjaDetail = '-';
        $nip = $emp->employeeDetail->nip ?? null;
        $pangkat = $emp->employeeDetail->pangkat_golongan ?? '';
        
        if($nip && strlen($nip) == 18) {
            try {
                $isPPPK = str_contains($pangkat, 'PPPK');
                
                if ($isPPPK) {
                    // PPPK: Ambil 4 Digit Tahun (8-12)
                    $yearStr = substr($nip, 8, 4); // YYYY
                    $startYear = (int)$yearStr;
                    $currYear = (int)date('Y');
                    $diffY = max(0, $currYear - $startYear);
                    
                    $masaKerjaText = $diffY . ' Thn';
                    // TMT hanya Tahun
                    $masaKerjaDetail = 'Tahun ' . $yearStr; 
                } else {
                    // PNS: Ambil 6 Digit (8-14)
                    $tmtStr = substr($nip, 8, 6);
                    $tmt = \Carbon\Carbon::createFromFormat('Ym', $tmtStr);
                    $diff = $tmt->diff(now());
                    $masaKerjaText = $diff->y . ' Thn ' . $diff->m . ' Bln';
                    $masaKerjaDetail = $tmt->isoFormat('MMMM Y');
                }
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

@if($employees instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <tr class="hidden">
        <td colspan="4">
            <div id="pagination-source">
                {!! $employees->withQueryString()->links() !!}
            </div>
        </td>
    </tr>
@endif
