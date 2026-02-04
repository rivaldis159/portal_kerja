<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function viewAny(User $user): bool { return $user->isSuperAdmin() || $user->isKepala() || $user->isAdminTim(); }
    public function create(User $user): bool { return $user->isSuperAdmin(); }
    public function update(User $user, User $model): bool { return $user->isSuperAdmin(); }
    public function delete(User $user, User $model): bool { return $user->isSuperAdmin(); }
}
