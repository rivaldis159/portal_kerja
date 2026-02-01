<?php

namespace App\Imports;

use App\Models\User;
use App\Models\EmployeeDetail; // Pastikan import model ini
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Imports\ImportPegawai;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;

class ImportPegawai implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // 1. Cek apakah email sudah ada? Jangan buat duplikat
        $existingUser = User::where('email', $row['email'])->first();

        if ($existingUser) {
            // Jika user sudah ada, kita skip atau bisa update (opsional)
            return null;
        }

        // 2. Buat User Baru (Tabel Users)
        $user = User::create([
            'name'     => $row['nama'],
            'email'    => $row['email'],
            'password' => Hash::make('12345678'), // Password default semua pegawai
            'role'     => 'pegawai', // Default role
            'team_id'  => null,      // Nanti diset manual admin, atau tambah kolom team_id di excel
        ]);

        // 3. Buat Detail Pegawai (Tabel Employee Details)
        EmployeeDetail::create([
            'user_id'           => $user->id,
            'nip'               => $row['nip_baru'] ?? null, // Sesuaikan dengan header Excel
            'nip_lama'          => $row['nip_lama'] ?? null,
            'nik'               => $row['nik'] ?? null,
            'pangkat_golongan'  => $row['pangkat'] ?? null,
            'jabatan'           => $row['jabatan'] ?? null,
            'bank_name'         => $row['nama_bank'] ?? 'BRI',
            'nomor_rekening'    => $row['no_rekening'] ?? null,
            'email_kantor'      => $row['email'], // Samakan dengan email login
            'alamat_tinggal'    => $row['alamat'] ?? null,
            'tempat_lahir'      => $row['tempat_lahir'] ?? null,
            // Format tanggal excel kadang angka, kita anggap user isi format YYYY-MM-DD text dulu
            // Atau biarkan null jika kosong
        ]);

        return $user;
    }
}
