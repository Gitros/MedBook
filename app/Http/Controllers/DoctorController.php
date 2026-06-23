<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with(['user', 'specializations'])->where('is_active', true);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")
                                                  ->orWhere('email', 'like', "%{$search}%"))
                  ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        if ($specId = $request->query('specialization')) {
            $query->whereHas('specializations', fn($q) => $q->where('specializations.id', $specId));
        }

        if ($request->query('max_fee')) {
            $query->where('consultation_fee', '<=', $request->query('max_fee'));
        }

        $doctors = $query->paginate(10)->withQueryString();
        $specializations = Specialization::where('is_active', true)->orderBy('name')->get();

        return view('doctors.index', compact('doctors', 'specializations', 'search'));
    }

    public function create()
    {
        $specializations = Specialization::where('is_active', true)->orderBy('name')->get();
        return view('doctors.create', compact('specializations'));
    }

    public function store(StoreDoctorRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'doctor',
            ]);

            $doctor = Doctor::create([
                'user_id' => $user->id,
                'license_number' => $request->license_number,
                'bio' => $request->bio,
                'consultation_fee' => $request->consultation_fee,
                'room' => $request->room,
            ]);

            $doctor->specializations()->sync($request->specializations);
        });

        return redirect()->route('doctors.index')->with('success', 'Lekarz dodany.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'specializations']);
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $doctor->load('specializations');
        $specializations = Specialization::where('is_active', true)->orderBy('name')->get();
        return view('doctors.edit', compact('doctor', 'specializations'));
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        DB::transaction(function () use ($request, $doctor) {
            $doctor->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $doctor->update([
                'license_number' => $request->license_number,
                'bio' => $request->bio,
                'consultation_fee' => $request->consultation_fee,
                'room' => $request->room,
                'is_active' => $request->boolean('is_active'),
            ]);

            $doctor->specializations()->sync($request->specializations);
        });

        return redirect()->route('doctors.index')->with('success', 'Lekarz zaktualizowany.');
    }

    public function destroy(Doctor $doctor)
    {
        $this->authorize('delete', $doctor);
        $doctor->update(['is_active' => false]);
        $doctor->delete();
        return redirect()->route('doctors.index')->with('success', 'Lekarz dezaktywowany.');
    }
}
