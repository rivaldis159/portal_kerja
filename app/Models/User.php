<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function isTeamAdmin($teamId)
    {
        return $this->teams()
            ->where('team_id', $teamId)
            ->wherePivot('role', 'admin')
            ->exists();
    }

    public function employeeDetail()
    {
        return $this->hasOne(EmployeeDetail::class);
    }

    public function isSuperAdmin()
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isKepala()
    {
        return $this->role === 'kepala';
    }

    public function isAdminTim()
    {
        return $this->teams()->wherePivot('role', 'admin')->exists();
    }

    public function getManagedTeamIds()
    {
        if ($this->isSuperAdmin() || $this->isKepala()) {
            return \App\Models\Team::pluck('id')->toArray();
        }
        return $this->teams()->wherePivot('role', 'admin')->pluck('teams.id')->toArray();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isSuperAdmin() || $this->isKepala() || $this->isAdminTim();
    }
}
