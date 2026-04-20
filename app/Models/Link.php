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
        'subcategory_id',
        'title',
        'url',
        'logo',
        'target',
        'is_active',
        'is_public',
        'is_vpn_required',
        'is_bps_pusat',
        'description',
        'type',
    ];

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function subcategory() {
        return $this->belongsTo(Subcategory::class);
    }
}