<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'icon',
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('joined_at')->withTimestamps();
    }

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    // Scopes
    public function scopeWithActiveLinks($query)
    {
        return $query->with(['links' => function ($q) {
            $q->where('is_active', true)->orderBy('order');
        }]);
    }

    // Methods
    public function addUser(User $user)
    {
        return $this->users()->attach($user->id, ['joined_at' => now()]);
    }

    public function removeUser(User $user)
    {
        return $this->users()->detach($user->id);
    }

    public function hasUser(User $user)
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }
}
