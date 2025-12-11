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
        'description',
        'icon',
        'color',
        'is_active',
        'is_public',
        'order',
        'open_new_tab',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'open_new_tab' => 'boolean',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeForTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    // Methods
    public function logAccess(User $user)
    {
        return $this->accessLogs()->create([
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function getClickCount($days = null)
    {
        $query = $this->accessLogs();

        if ($days) {
            $query->where('accessed_at', '>=', now()->subDays($days));
        }

        return $query->count();
    }

    public function scopeVisibleToUser($query, $user)
    {
        // Jika tidak login (Guest), hanya link publik
        if (!$user) {
            return $query->where('is_public', true);
        }

        // Jika Super Admin, lihat semua (Opsional, hapus if ini jika super admin juga terbatas)
        if ($user->role === 'super_admin') {
            return $query;
        }

        // User Biasa/Admin Tim: Link Publik ATAU Link milik tim user tersebut
        return $query->where(function ($q) use ($user) {
            $q->where('is_public', true)
            ->orWhereIn('team_id', $user->teams->pluck('id'));
        });
    }
}
