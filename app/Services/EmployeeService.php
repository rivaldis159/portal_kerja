<?php

namespace App\Services;

use App\Models\User;
use App\Models\EmployeeDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EmployeeService
{
    /**
     * Update user profile and employee details.
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateProfile(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $user->name = $data['name'];
            $user->email = $data['email'];
            
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            
            $user->save();

            $detailFields = [
                'nip', 'nip_lama', 'pangkat_golongan', 'tmt_pangkat',
                'masa_kerja_tahun', 'masa_kerja_bulan', 'jabatan',
                'pendidikan_strata', 'pendidikan_jurusan',
                'tempat_lahir', 'tanggal_lahir', 'nik',
                'alamat_tinggal', 'status_perkawinan', 'nama_pasangan',
                'bank_name', 'nomor_rekening'
            ];

            $detailData = array_intersect_key($data, array_flip($detailFields));

            EmployeeDetail::updateOrCreate(
                ['user_id' => $user->id],
                $detailData
            );

            return $user;
        });
    }
}
