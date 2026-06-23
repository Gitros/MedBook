<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpecializationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Read-only dla zalogowanych
    Route::get('/specializations', [SpecializationController::class, 'index'])->name('specializations.index');
    Route::get('/specializations/{specialization}', [SpecializationController::class, 'show'])->name('specializations.show');

    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');

    // Appointments — listing per rola obsługiwany w controllerze
    Route::get('/appointments/export/csv', [AppointmentController::class, 'exportCsv'])->name('appointments.export');
    Route::get('/appointments/{appointment}/pdf', [AppointmentController::class, 'downloadPdf'])->name('appointments.pdf');
    Route::resource('appointments', AppointmentController::class)->except(['edit']);
    Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');

    // Pacjent edytuje własny profil
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');

    // Tylko admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/specializations-create', [SpecializationController::class, 'create'])->name('specializations.create');
        Route::post('/specializations', [SpecializationController::class, 'store'])->name('specializations.store');
        Route::get('/specializations/{specialization}/edit', [SpecializationController::class, 'edit'])->name('specializations.edit');
        Route::put('/specializations/{specialization}', [SpecializationController::class, 'update'])->name('specializations.update');
        Route::delete('/specializations/{specialization}', [SpecializationController::class, 'destroy'])->name('specializations.destroy');

        Route::get('/doctors-create', [DoctorController::class, 'create'])->name('doctors.create');
        Route::post('/doctors', [DoctorController::class, 'store'])->name('doctors.store');
        Route::get('/doctors/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
        Route::put('/doctors/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
        Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');

        Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
        Route::get('/patients-create', [PatientController::class, 'create'])->name('patients.create');
        Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
        Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    });
});

require __DIR__.'/auth.php';
