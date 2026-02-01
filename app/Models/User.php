<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'team_id', // Tambahan
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi
    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function employeeDetail() {
        return $this->hasOne(EmployeeDetail::class);
    }

    // Cek Role
    public function isSuperAdmin() { return $this->role === 'super_admin'; }
    public function isKepala() { return $this->role === 'kepala'; }
    public function isAdminTim() { return $this->role === 'admin_tim'; }
}