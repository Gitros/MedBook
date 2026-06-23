<?php

namespace App\Policies;

use App\Models\Specialization;
use App\Models\User;

class SpecializationPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Specialization $s): bool { return true; }
    public function create(User $user): bool { return $user->isAdmin(); }
    public function update(User $user, Specialization $s): bool { return $user->isAdmin(); }
    public function delete(User $user, Specialization $s): bool { return $user->isAdmin(); }
    public function restore(User $user, Specialization $s): bool { return $user->isAdmin(); }
}
