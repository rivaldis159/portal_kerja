<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',     // <--- Wajib ada
        'team_id',  // <--- Wajib ada
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELASI (PENTING) ---
    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function employeeDetail() {
        return $this->hasOne(EmployeeDetail::class);
    }

    // --- HELPER ROLE (PENTING UNTUK FILAMENT) ---
    public function isSuperAdmin() { return $this->role === 'super_admin'; }
    public function isKepala() { return $this->role === 'kepala'; }
    public function isAdminTim() { return $this->role === 'admin_tim'; }
}