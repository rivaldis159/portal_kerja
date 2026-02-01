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
        'role',
        // 'team_id', // Kita hapus ini karena sudah pakai tabel pivot team_user
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

    // RELASI BARU: BISA BANYAK TIM
    public function teams()
    {
        // withPivot('role') artinya kita ikut ambil kolom role dari tabel penghubung
        return $this->belongsToMany(Team::class, 'team_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    // Helper untuk cek apakah dia admin di tim tertentu
    public function isTeamAdmin($teamId)
    {
        // Cek apakah di relasi teams, ada yg ID-nya sekian DAN role-nya 'admin'
        return $this->teams()
            ->where('team_id', $teamId)
            ->wherePivot('role', 'admin')
            ->exists();
    }

    public function employeeDetail()
    {
        return $this->hasOne(EmployeeDetail::class);
    }

    // Helper Role
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }
    public function isKepala()
    {
        return $this->role === 'kepala';
    }
    public function isAdminTim()
    {
        return $this->role === 'admin_tim';
    }
}
