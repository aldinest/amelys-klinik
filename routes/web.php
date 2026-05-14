<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

// =========================================================
// CONTROLLERS IMPORT
// =========================================================

// --- Public Controllers ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsUpdateController;
use App\Http\Controllers\WelcomeController;

// --- Admin Controllers ---
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PatientController as AdminPatient;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DoctorDisplayScheduleController;
use App\Http\Controllers\Admin\NewsController;

// --- Pengurus Controllers ---
use App\Http\Controllers\Pengurus\PengurusDashboardController;
use App\Http\Controllers\Pengurus\PatientController as PengurusPatient;
use App\Http\Controllers\Pengurus\DoctorScheduleController;
use App\Http\Controllers\Pengurus\ReservationController as PengurusReservation;
use App\Http\Controllers\Pengurus\MedicalRecordController;
use App\Http\Controllers\Pengurus\ReportController;

// --- Pasien Controllers ---
use App\Http\Controllers\Pasien\PatientDashboardController;
use App\Http\Controllers\Pasien\ReservationController as PasienReservation;
use App\Http\Controllers\Pasien\MedicalRecordController as PasienMedicalRecord;
use App\Http\Controllers\Pasien\ProfileController as PasienProfile;

// =========================================================
// ROUTES DEFINITION
// =========================================================

/**
 * HALAMAN PUBLIK (TANPA LOGIN)
 * Menampilkan landing page, berita, dan jadwal umum.
 */
Route::prefix('/')->group(function () {
    Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
});


/**
 * ROLE: ADMIN
 * Manajemen User, Dokter, Pasien, dan Berita.
 */
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Manajemen Pasien (Import, Export, PDF)
        Route::post('/patients/import', [AdminPatient::class, 'import'])->name('patients.import');
        Route::get('/patients/export', [AdminPatient::class, 'export'])->name('patients.export');
        Route::get('/patients/pdf', [AdminPatient::class, 'pdf'])->name('patients.pdf');
        Route::resource('patients', AdminPatient::class);

        // Manajemen Dokter
        Route::resource('doctors', DoctorController::class);
        Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
        Route::post('/doctors/{doctor}/create-account', [DoctorController::class, 'createAccount'])->name('doctors.create-account');

        // Manajemen User & Account
        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

        // Manajemen Display Jadwal (Toggle Status)
        Route::resource('doctor-display-schedules', DoctorDisplayScheduleController::class)->except(['show']);
        Route::patch('doctor-display-schedules/{doctorDisplaySchedule}/toggle', [DoctorDisplayScheduleController::class, 'toggle'])->name('doctor-display-schedules.toggle');

        // Manajemen Berita
        Route::resource('news', NewsController::class);
    });

/**
 * ROLE: PENGURUS
 * Operasional harian: Reservasi, Rekam Medis, dan Jadwal Dokter.
 */
Route::middleware(['auth', 'role:pengurus'])
    ->prefix('pengurus')
    ->name('pengurus.')
    ->group(function () {

        // Dashboard Pengurus
        Route::get('/dashboard', [PengurusDashboardController::class, 'index'])->name('dashboard');

        // Manajemen Pasien & Laporan
        Route::get('/patients', [PengurusPatient::class, 'index'])->name('patients.index');
        Route::post('/patients/import', [PengurusPatient::class, 'import'])->name('patients.import');
        Route::get('/patients/export', [PengurusPatient::class, 'export'])->name('patients.export');
        Route::get('/patients/pdf', [PengurusPatient::class, 'pdf'])->name('patients.pdf');
        Route::get('/patients/{patient}', [PengurusPatient::class, 'show'])->name('patients.show');

        Route::get('/report/pdf', [ReportController::class, 'exportPdf'])->name('report.pdf');
        Route::get('/report/export-pdf/{month}/{year}', [ReportController::class, 'exportMonthlyPdf'])->name('report.export_monthly_pdf');
        Route::get('/report/excel', [ReportController::class, 'exportExcel'])->name('report.excel');
        
        // Export Reservasi
        Route::get('/reservations/export-pdf/{schedule_id}', [PengurusReservation::class, 'exportPdf'])->name('reservations.export-pdf');
        Route::get('/reservations/export-excel/{schedule_id}', [PengurusReservation::class, 'exportExcel'])->name('reservations.export-excel');

        // Operasional
        Route::resource('doctor_schedules', DoctorScheduleController::class);
        Route::resource('reservations', PengurusReservation::class);
        Route::delete('/reservations/{reservation}/cancel', [PengurusReservation::class, 'cancel'])->name('reservations.cancel');

        // Rekap History
        Route::get('/report', [ReportController::class, 'index'])->name('report.index');

        // Rekam Medis (Akses Terbatas)
        Route::resource('medical-records', MedicalRecordController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('medical-records/{id}/edit', [MedicalRecordController::class, 'edit'])->name('medical-records.edit');
        Route::put('medical-records/{id}', [MedicalRecordController::class, 'update'])->name('medical-records.update');
        
    });

/**
 * ROLE: PASIEN
 * Fitur untuk pasien: Booking, Lihat Rekam Medis, dan Profile.
 */
Route::middleware(['auth', 'role:pasien'])
    ->prefix('pasien')
    ->name('pasien.')
    ->group(function () {

        // Dashboard Pasien
        Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
        
        // Reservasi & Kalender
        Route::resource('reservations', PasienReservation::class);
        Route::get('reservations/calendar/{doctor}', [PasienReservation::class, 'calendar'])->name('reservations.calendar');
        
        // Rekam Medis Pribadi
        Route::resource('medical-records', PasienMedicalRecord::class);

        // Profile Pasien
        Route::get('/profile', [PasienProfile::class, 'index'])->name('profile.index');
        Route::put('/profile/update', [PasienProfile::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [PasienProfile::class, 'updatePassword'])->name('profile.password');
    });

/**
 * GLOBAL DASHBOARD REDIRECT
 * Mengatur arah landing setelah login berdasarkan role user.
 */
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) return redirect('/login');

    if ($user->isAdmin()) return redirect()->route('admin.dashboard');
    if ($user->isPengurus()) return redirect()->route('pengurus.dashboard');
    if ($user->isPasien()) return redirect()->route('pasien.dashboard');

    abort(403);
})->middleware(['auth'])->name('dashboard');

/**
 * AUTH PROFILE (DEFAULT LARAVEL BREEZE/JETSTREAM)
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Load Auth Routes
require __DIR__.'/auth.php';