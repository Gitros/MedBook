<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Patient::class);

        $query = Patient::with('user');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")
                                                  ->orWhere('email', 'like', "%{$search}%"))
                  ->orWhere('pesel', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->query('status') === 'inactive') {
            $query->where('is_active', false);
        } elseif ($request->query('status') !== 'all') {
            $query->where('is_active', true);
        }

        $patients = $query->paginate(10)->withQueryString();

        return view('patients.index', compact('patients', 'search'));
    }

    public function create()
    {
        $this->authorize('create', Patient::class);
        return view('patients.create');
    }

    public function store(StorePatientRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'patient',
            ]);

            Patient::create([
                'user_id' => $user->id,
                'pesel' => $request->pesel,
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'address' => $request->address,
            ]);
        });

        return redirect()->route('patients.index')->with('success', 'Pacjent dodany.');
    }

    public function show(Patient $patient)
    {
        $this->authorize('view', $patient);
        $patient->load(['user', 'appointments.doctor.user', 'appointments.specialization']);
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $this->authorize('update', $patient);
        return view('patients.edit', compact('patient'));
    }

    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        DB::transaction(function () use ($request, $patient) {
            $patient->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $data = [
                'pesel' => $request->pesel,
                'phone' => $request->phone,
                'birth_date' => $request->birth_date,
                'address' => $request->address,
            ];

            if ($request->user()->isAdmin()) {
                $data['is_active'] = $request->boolean('is_active');
            }

            $patient->update($data);
        });

        $route = $request->user()->isAdmin() ? 'patients.index' : 'patients.show';
        return redirect()->route($route, $request->user()->isAdmin() ? [] : ['patient' => $patient])
            ->with('success', 'Dane zaktualizowane.');
    }

    public function destroy(Patient $patient)
    {
        $this->authorize('delete', $patient);
        $patient->update(['is_active' => false]);
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Pacjent dezaktywowany.');
    }

    private function authorizeView(Patient $patient): void
    {
        $user = auth()->user();
        if (!$user) abort(403);
        if ($user->isAdmin()) return;
        if ($user->isPatient() && $patient->user_id === $user->id) return;
        if ($user->isDoctor()) return; // lekarze widzą pacjentów (wizyty)
        abort(403);
    }
}
