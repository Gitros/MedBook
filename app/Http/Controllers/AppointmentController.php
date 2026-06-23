<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Specialization;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Appointment::with(['doctor.user', 'patient.user', 'specialization']);

        if ($user->isDoctor()) {
            $query->whereHas('doctor', fn($q) => $q->where('user_id', $user->id));
        } elseif ($user->isPatient()) {
            $query->whereHas('patient', fn($q) => $q->where('user_id', $user->id));
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->query('search')) {
            $query->where('reason', 'like', "%{$search}%");
        }
        if ($from = $request->query('from')) {
            $query->where('appointment_date', '>=', $from);
        }
        if ($to = $request->query('to')) {
            $query->where('appointment_date', '<=', $to . ' 23:59:59');
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(15)->withQueryString();

        return view('appointments.index', compact('appointments'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Appointment::class);

        $doctors = Doctor::with(['user', 'specializations'])->where('is_active', true)->get();
        $specializations = Specialization::where('is_active', true)->orderBy('name')->get();
        $preselectedDoctor = $request->query('doctor_id');

        return view('appointments.create', compact('doctors', 'specializations', 'preselectedDoctor'));
    }

    public function store(StoreAppointmentRequest $request)
    {
        $user = auth()->user();
        $patientId = $user->isPatient() ? $user->patient->id : $request->patient_id;

        if (!$patientId) {
            return back()->withErrors(['patient_id' => 'Wybierz pacjenta.'])->withInput();
        }

        Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $patientId,
            'specialization_id' => $request->specialization_id,
            'appointment_date' => $request->appointment_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('appointments.index')->with('success', 'Wizyta zarezerwowana.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        $appointment->load(['doctor.user', 'patient.user', 'specialization']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        return view('appointments.edit', compact('appointment'));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $data = ['status' => $request->status];

        if (auth()->user()->isDoctor() || auth()->user()->isAdmin()) {
            $data['notes'] = $request->notes;
        }
        if ($request->filled('appointment_date')) {
            $data['appointment_date'] = $request->appointment_date;
        }

        $appointment->update($data);

        return redirect()->route('appointments.index')->with('success', 'Wizyta zaktualizowana.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $appointment->update(['status' => 'cancelled']);
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Wizyta odwołana.');
    }

    public function downloadPdf(Appointment $appointment)
    {
        $this->authorize('downloadPdf', $appointment);
        $appointment->load(['doctor.user', 'patient.user', 'specialization']);
        $pdf = Pdf::loadView('appointments.pdf', compact('appointment'));
        return $pdf->download("wizyta-{$appointment->id}.pdf");
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $this->authorize('exportCsv', Appointment::class);

        $user = auth()->user();
        $query = Appointment::with(['doctor.user', 'patient.user', 'specialization']);
        if ($user->isDoctor()) {
            $query->whereHas('doctor', fn($q) => $q->where('user_id', $user->id));
        }

        $filename = 'wizyty-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            // BOM dla Excela żeby polskie znaki działały
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, ['ID', 'Data', 'Lekarz', 'Pacjent', 'Specjalizacja', 'Status', 'Powód', 'Notatki'], ';');
            $query->orderBy('appointment_date', 'desc')->chunk(200, function ($chunk) use ($out) {
                foreach ($chunk as $a) {
                    fputcsv($out, [
                        $a->id,
                        $a->appointment_date->format('Y-m-d H:i'),
                        $a->doctor->user->name,
                        $a->patient->user->name,
                        $a->specialization->name ?? '',
                        $a->status,
                        $a->reason,
                        $a->notes ?? '',
                    ], ';');
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
