<?php

namespace App\Imports;

use App\Models\User;
use App\Models\EmployeeDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPegawai implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        Log::info('Mencoba import baris:', $row);

        if (empty($row['nama']) || empty($row['email'])) {
            Log::warning('Baris dilewati karena Nama atau Email kosong.');
            return null;
        }

        $existingUser = User::where('email', $row['email'])->first();
        if ($existingUser) {
            return null;
        }

        try {
            $user = User::create([
                'name'     => $row['nama'],
                'email'    => $row['email'],
                'password' => Hash::make('12345678'),
                'role'     => 'pegawai',
                'team_id'  => null,
            ]);

            EmployeeDetail::create([
                'user_id'           => $user->id,
                'nip'               => $row['nip_baru'] ?? null,
                'nip_lama'          => $row['nip_lama'] ?? null,
                'nik'               => $row['nik'] ?? null,
                'pangkat_golongan'  => $row['pangkat'] ?? null,
                'jabatan'           => $row['jabatan'] ?? null,
                'bank_name'         => $row['nama_bank'] ?? 'BRI',
                'nomor_rekening'    => $row['no_rekening'] ?? null,
                'email_kantor'      => $row['email'],
                'alamat_tinggal'    => $row['alamat'] ?? null,
                'tempat_lahir'      => $row['tempat_lahir'] ?? null,
            ]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Gagal import: ' . $e->getMessage());
            return null;
        }
    }
}
