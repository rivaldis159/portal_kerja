<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'title',
        'content',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('team_id');
    }

    public function scopeForTeam($query, $teamId)
    {
        return $query->where(function ($q) use ($teamId) {
            $q->whereNull('team_id')
              ->orWhere('team_id', $teamId);
        });
    }

    public function scopeOrderedByPriority($query)
    {
        return $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'normal', 'low')")
                     ->orderBy('created_at', 'desc');
    }

    // Methods
    public function isGlobal()
    {
        return is_null($this->team_id);
    }

    public function getPriorityColorAttribute()
    {
        return [
            'low' => 'gray',
            'normal' => 'blue',
            'high' => 'yellow',
            'urgent' => 'red',
        ][$this->priority] ?? 'gray';
    }
}
