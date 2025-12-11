<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements FilamentUser // TAMBAH implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot('joined_at')->withTimestamps();
    }

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function updateLastLogin()
    {
        $this->update(['last_login' => now()]);
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Izinkan jika role-nya ada isinya (super_admin atau admin_tim)
        return ! is_null($this->role);
        
        // ATAU jika ingin lebih spesifik:
        // return in_array($this->role, ['super_admin', 'admin_tim']);
    }
}
