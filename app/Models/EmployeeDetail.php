<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDetail extends Model
{
    protected $guarded = []; // Semua kolom boleh diisi

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}