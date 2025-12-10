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

    // Relationships
    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot('joined_at')->withTimestamps();
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

    // Methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function updateLastLogin()
    {
        $this->update(['last_login' => now()]);
    }

    // TAMBAH METHOD INI UNTUK FILAMENT ACCESS
    public function canAccessPanel(Panel $panel): bool
    {
        Log::info('Checking panel access for user: ' . $this->email);
        Log::info('Role: ' . $this->role);
        Log::info('Is Active: ' . $this->is_active);

        return $this->role === 'admin' && $this->is_active;
    }
}
