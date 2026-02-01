<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDetail extends Model
{
    protected $guarded = []; // Izinkan semua kolom diisi secara massal

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Helper untuk mengambil opsi Pangkat BKN
    public static function getPangkatOptions()
    {
        return [
            'Juru Muda (I/a)' => 'Juru Muda (I/a)',
            'Juru Muda Tingkat I (I/b)' => 'Juru Muda Tingkat I (I/b)',
            'Juru (I/c)' => 'Juru (I/c)',
            'Juru Tingkat I (I/d)' => 'Juru Tingkat I (I/d)',
            'Pengatur Muda (II/a)' => 'Pengatur Muda (II/a)',
            'Pengatur Muda Tingkat I (II/b)' => 'Pengatur Muda Tingkat I (II/b)',
            'Pengatur (II/c)' => 'Pengatur (II/c)',
            'Pengatur Tingkat I (II/d)' => 'Pengatur Tingkat I (II/d)',
            'Penata Muda (III/a)' => 'Penata Muda (III/a)',
            'Penata Muda Tingkat I (III/b)' => 'Penata Muda Tingkat I (III/b)',
            'Penata (III/c)' => 'Penata (III/c)',
            'Penata Tingkat I (III/d)' => 'Penata Tingkat I (III/d)',
            'Pembina (IV/a)' => 'Pembina (IV/a)',
            'Pembina Tingkat I (IV/b)' => 'Pembina Tingkat I (IV/b)',
            'Pembina Utama Muda (IV/c)' => 'Pembina Utama Muda (IV/c)',
            'Pembina Utama Madya (IV/d)' => 'Pembina Utama Madya (IV/d)',
            'Pembina Utama (IV/e)' => 'Pembina Utama (IV/e)',
        ];
    }

    // Helper untuk Jabatan
    public static function getJabatanOptions()
    {
        $list = [
            'Kepala BPS Kabupaten Dairi', 'Kepala Subbagian Umum', 'Penata Laksana Barang Terampil',
            'Analis Pengelola Keuangan APBN Ahli Muda', 'Pranata Keuangan APBN Mahir',
            'Analis Anggaran Ahli Muda', 'Analis Anggaran Ahli Pertama', 'Pustakawan Mahir',
            'Pustakawan Terampil', 'Pengelola Barang Milik Negara', 'Pengolah Data', 'Pranata Kearsipan',
            'Statistisi Ahli Madya', 'Statistisi Ahli Muda', 'Statistisi Ahli Pertama',
            'Statistisi Mahir', 'Statistisi Terampil', 'Pranata Komputer Ahli Madya',
            'Pranata Komputer Ahli Muda', 'Pranata Komputer Ahli Pertama', 'Pranata Komputer Penyelia',
            'Pranata Komputer Mahir', 'Pranata Komputer Terampil'
        ];
        // Mengubah array menjadi key => value sama
        return array_combine($list, $list);
    }
}