<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenawaranKerja extends Model
{
    use HasFactory;
    
    protected $table = 'contracts';
    protected $guarded = ['id'];

    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class);
    }

    public function activity()
    {
        return $this->belongsTo(\App\Models\Activity::class);
    }

    public function mitra()
    {
        return $this->belongsTo(\App\Models\Mitra::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function penilaian()
    {
        return $this->hasOne(PenilaianKinerja::class, 'contract_id');
    }
}
