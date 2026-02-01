<?php

namespace App\Imports;

use App\Models\User;
use App\Models\EmployeeDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // <--- Tambah ini
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPegawai implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. Catat ke Log (Cek storage/logs/laravel.log nanti)
        Log::info('Mencoba import baris:', $row);

        // Pastikan kolom 'nama' dan 'email' ada isinya
        if (empty($row['nama']) || empty($row['email'])) {
            Log::warning('Baris dilewati karena Nama atau Email kosong.');
            return null;
        }

        // 2. Cek apakah email sudah ada?
        $existingUser = User::where('email', $row['email'])->first();
        if ($existingUser) {
            Log::info('User sudah ada, skip: ' . $row['email']);
            return null;
        }

        // 3. Buat User
        try {
            $user = User::create([
                'name'     => $row['nama'],
                'email'    => $row['email'],
                'password' => Hash::make('12345678'),
                'role'     => 'pegawai',
                'team_id'  => null,
            ]);

            // 4. Buat Detail
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

            Log::info('SUKSES import pegawai: ' . $row['nama']);

            return $user;
        } catch (\Exception $e) {
            Log::error('GAGAL import baris ini. Error: ' . $e->getMessage());
            return null;
        }
    }
}
