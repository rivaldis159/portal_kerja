<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function create(User $user): bool { return $user->isSuperAdmin(); }
    public function update(User $user, Team $team): bool 
    { 
        return $user->isSuperAdmin() || $team->users->contains($user->id);
    }
    public function delete(User $user, Team $team): bool { return $user->isSuperAdmin(); }
}
