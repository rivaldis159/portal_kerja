<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'category_id',
        'title',
        'url',
        'target',
        'is_active',
        'is_public',
        'is_vpn_required',
        'is_bps_pusat',
        'description',
    ];

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}