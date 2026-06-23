<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function viewAny(User $user): bool { return true; }

    public function view(User $user, Appointment $a): bool
    {
        if ($user->isAdmin()) return true;
        if ($user->isDoctor()) return $a->doctor->user_id === $user->id;
        return $user->isPatient() && $a->patient->user_id === $user->id;
    }

    public function create(User $user): bool { return $user->isPatient() || $user->isAdmin(); }

    public function update(User $user, Appointment $a): bool
    {
        if ($user->isAdmin()) return true;
        if ($user->isDoctor()) return $a->doctor->user_id === $user->id;
        return $user->isPatient() && $a->patient->user_id === $user->id;
    }

    public function delete(User $user, Appointment $a): bool { return $this->view($user, $a); }
    public function exportCsv(User $user): bool { return $user->isAdmin() || $user->isDoctor(); }
    public function downloadPdf(User $user, Appointment $a): bool { return $this->view($user, $a); }
}
