<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    public function viewAny(User $user): bool { return $user->isAdmin() || $user->isDoctor(); }

    public function view(User $user, Patient $p): bool
    {
        if ($user->isAdmin() || $user->isDoctor()) return true;
        return $user->isPatient() && $p->user_id === $user->id;
    }

    public function create(User $user): bool { return $user->isAdmin(); }

    public function update(User $user, Patient $p): bool
    {
        if ($user->isAdmin()) return true;
        return $user->isPatient() && $p->user_id === $user->id;
    }

    public function delete(User $user, Patient $p): bool { return $user->isAdmin(); }
    public function restore(User $user, Patient $p): bool { return $user->isAdmin(); }
}
