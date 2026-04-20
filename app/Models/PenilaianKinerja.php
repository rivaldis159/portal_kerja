<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianKinerja extends Model
{
    use HasFactory;

    protected $table = 'penilaians';
    protected $guarded = ['id'];

    public function contract()
    {
        return $this->belongsTo(PenawaranKerja::class, 'contract_id');
    }

    public function rater()
    {
        return $this->belongsTo(User::class, 'rated_by');
    }
}
