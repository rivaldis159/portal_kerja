<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'order',
        'is_active',
        'is_locked',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_locked' => 'boolean',
    ];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name);
    }
}
