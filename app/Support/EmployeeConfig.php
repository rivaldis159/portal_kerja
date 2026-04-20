<?php

namespace App\Support;

class EmployeeConfig
{
    public static function getPangkatOrder()
    {
        return [
            'Pembina Utama (IV/e)',
            'PPPK Ahli Utama (XV)',
            'Pembina Utama Madya (IV/d)', 'Pembina Utama Muda (IV/c)', 'Pembina Tingkat I (IV/b)', 'Pembina (IV/a)', 'Pembina (V/a)',
            'PPPK Ahli Madya (XIII)',
            'Penata Tingkat I (III/d)', 'Penata (III/c)',
            'PPPK Ahli Muda (XI)',
            'Penata Muda Tingkat I (III/b)', 'Penata Muda (III/a)',
            'PPPK Ahli Pertama (IX)',
            'Pengatur Tingkat I (II/d)', 'Pengatur (II/c)',
            'PPPK Terampil (VII)',
            'Pengatur Muda Tingkat I (II/b)', 'Pengatur Muda (II/a)',
            'Juru Tingkat I (I/d)', 'Juru (I/c)',
            'PPPK Penata Layanan Operasional (V)', 'PPPK Pengelola Umum (V)',
            'Juru Muda Tingkat I (I/b)', 'Juru Muda (I/a)',
        ];
    }

    public static function getPendidikanOrder()
    {
        return ['SMA/SMK', 'D-I', 'D-II', 'D-III', 'D-IV', 'S-1', 'S-2', 'S-3'];
    }
}
