<?php

namespace App\Imports;

use App\Models\Mitra;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class MitraImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            Log::warning('Import collection is empty.');
            return;
        }

        foreach ($rows as $row) {
            $nama = $row['nama_lengkap'] ?? null;

            if (empty($nama) || $nama === '-' || str_contains($nama, 'Umur dihitung') || str_contains($nama, 'Keterangan') || str_contains($nama, 'No ID')) {
                continue;
            }

            $data = [
                'nama' => $row['nama_lengkap'],
                'posisi' => $row['posisi_daftar'] ?? $row['posisi'],
                'status_seleksi' => $row['status_seleksi_1terpilih_2tidak_terpilih'] ?? null,
                'alamat_detail' => $row['alamat_detail'] ?? null,
                'alamat_kec' => $row['alamat_kec'] ?? null,
                'alamat_desa' => $row['alamat_desa'] ?? null,
                'no_telp' => $row['no_telp'] ?? null,
                'email' => $row['email'] ?? null,
                'sobat_id' => $row['sobat_id'] ?? null,
                'jenis_kelamin' => $this->normalizeGender($row['jenis_kelamin'] ?? null),
                'pendidikan' => $row['pendidikan'] ?? null,
                'pekerjaan' => $row['pekerjaan'] ?? null,
                'deskripsi_pekerjaan_lain' => $row['deskripsi_pekerjaan_lain'] ?? null,
            ];

            $ttlRaw = $row['tempat_tanggal_lahir_umur'] ?? null;
            if ($ttlRaw) {
                $parts = explode(',', $ttlRaw);
                if (count($parts) >= 2) {
                    $data['tempat_lahir'] = trim($parts[0]);

                    $datePart = trim($parts[1]);
                    $datePart = preg_replace('/\s*\(.*?\)/', '', $datePart);

                    $months = [
                        'Januari' => 'January', 'Februari' => 'February', 'Maret' => 'March',
                        'April' => 'April', 'Mei' => 'May', 'Juni' => 'June',
                        'Juli' => 'July', 'Agustus' => 'August', 'September' => 'September',
                        'Oktober' => 'October', 'November' => 'November', 'Desember' => 'December',
                        'Ags' => 'August', 'Okt' => 'October', 'Des' => 'December'
                    ];

                    $dateStr = strtr($datePart, $months);

                    try {
                        $timestamp = strtotime($dateStr);
                        if ($timestamp) {
                            $data['tanggal_lahir'] = date('Y-m-d', $timestamp);
                        }
                    } catch (\Exception $e) {
                        Log::warning("Date parse failed for: " . $datePart);
                    }
                } else {
                    $data['tempat_lahir'] = $ttlRaw;
                }
            }

            if (!empty($data['sobat_id'])) {
                $mitra = Mitra::where('sobat_id', $data['sobat_id'])->first();
                if ($mitra) {
                    $mitra->update($data);
                    continue;
                }
            }

            Mitra::create($data);
        }
    }

    private function normalizeGender($val)
    {
        if (!$val) return null;

        $val = strtoupper(trim($val));

        if (in_array($val, ['L', 'LK', 'LAKI-LAKI', 'LAKI LAKI', 'PRIA'])) {
            return 'L';
        }

        if (in_array($val, ['P', 'PR', 'PEREMPUAN', 'WANITA'])) {
            return 'P';
        }

        return null;
    }
}
