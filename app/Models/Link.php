<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'team_id',
        'category_id',
        'title',
        'url',
        'target',
        'is_active',
        'is_public',
        'is_vpn_required', // Baru
        'is_bps_pusat',    // Baru
    ];

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}