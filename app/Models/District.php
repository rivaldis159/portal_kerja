<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function villages()
    {
        return $this->hasMany(Village::class);
    }

    public function mitras()
    {
        return $this->hasMany(Mitra::class);
    }
}
