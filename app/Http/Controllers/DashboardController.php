<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $stats = [
                'doctors' => Doctor::where('is_active', true)->count(),
                'patients' => Patient::where('is_active', true)->count(),
                'specializations' => Specialization::where('is_active', true)->count(),
                'appointments_today' => Appointment::whereDate('appointment_date', today())->count(),
                'appointments_pending' => Appointment::where('status', 'pending')->count(),
            ];
            $recent = Appointment::with(['doctor.user', 'patient.user'])->latest()->take(5)->get();
            return view('dashboards.admin', compact('stats', 'recent'));
        }

        if ($user->isDoctor()) {
            $doctor = $user->doctor;
            if (!$doctor) {
                abort(500, 'Brak profilu lekarza dla tego konta. Skontaktuj się z administratorem.');
            }
            $today = Appointment::with('patient.user')
                ->where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())
                ->orderBy('appointment_date')->get();
            $upcoming = Appointment::with('patient.user')
                ->where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', '>', today())
                ->whereIn('status', ['pending', 'confirmed'])
                ->orderBy('appointment_date')->take(10)->get();
            return view('dashboards.doctor', compact('doctor', 'today', 'upcoming'));
        }

        // patient
        $patient = $user->patient;
        if (!$patient) {
            abort(500, 'Brak profilu pacjenta dla tego konta. Wyloguj się i zarejestruj ponownie lub skontaktuj z administratorem.');
        }
        $upcoming = Appointment::with(['doctor.user', 'specialization'])
            ->where('patient_id', $patient->id)
            ->where('appointment_date', '>=', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')->get();
        $past = Appointment::with(['doctor.user'])
            ->where('patient_id', $patient->id)
            ->where('appointment_date', '<', now())
            ->orderBy('appointment_date', 'desc')->take(5)->get();
        return view('dashboards.patient', compact('patient', 'upcoming', 'past'));
    }
}
