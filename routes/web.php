<?php

//Public
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsUpdateController;
use App\Http\Controllers\WelcomeController;

//Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PatientController as AdminPatient;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DoctorDisplayScheduleController;
use App\Http\Controllers\Admin\NewsController;

//Pengurus
use App\Http\Controllers\Pengurus\PengurusDashboardController;
use App\Http\Controllers\Pengurus\PatientController as PengurusPatient;
use App\Http\Controllers\Pengurus\DoctorScheduleController;
use App\Http\Controllers\Pengurus\ReservationController as PengurusReservation;
use App\Http\Controllers\Pengurus\MedicalRecordController;

//Pasien
use App\Http\Controllers\Pasien\PatientDashboardController;
use App\Http\Controllers\Pasien\ReservationController as PasienReservation;
use App\Http\Controllers\Pasien\MedicalRecordController as PasienMedicalRecord;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route Dashboard awal
// Group untuk Halaman Publik (Tanpa Login)
Route::prefix('/')->group(function () {
    // Semua data (News & Schedules) diproses di sini
    Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
    // Kamu bisa tambah route publik lain di sini nanti, misal:
    // Route::get('/tentang-kami', [PublicDisplayController::class, 'about']);
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        //Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        //Data Dokter
        Route::resource('doctors', DoctorController::class);
        Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])
            ->name('doctors.show');
        Route::post('/doctors/{doctor}/create-account',
            [DoctorController::class, 'createAccount']
            )->name('doctors.create-account');

        //Data Pasien
        Route::resource('patients', AdminPatient::class);
        Route::post('/patients/{patient}/create-account',
            [AdminPatient::class, 'createAccount']
            )->name('patients.create-account');

        //Data User
        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword']
            )->name('users.reset-password');

        //Display Jadwal Dokter
        Route::resource('doctor-display-schedules',DoctorDisplayScheduleController::class
            )->except(['show']);
        Route::patch('doctor-display-schedules/{doctorDisplaySchedule}/toggle',[DoctorDisplayScheduleController::class, 'toggle']
            )->name('doctor-display-schedules.toggle');

        //Update News
        Route::resource('news', NewsController::class);

    });

Route::middleware(['auth', 'role:pengurus'])
    ->prefix('pengurus')
    ->name('pengurus.')
    ->group(function () {

        //Dashboard
        Route::get('/dashboard', [PengurusDashboardController::class, 'index'])
            ->name('dashboard');

        //Data Pasien
        Route::get('/patients', [PengurusPatient::class, 'index'])
            ->name('patients.index');
        Route::post('/patients/import', [PengurusPatient::class, 'import'])
            ->name('patients.import');
        Route::get('/patients/export', [PengurusPatient::class, 'export'])
            ->name('patients.export');
        Route::get('/patients/pdf', [PengurusPatient::class, 'pdf'])
            ->name('patients.pdf');
        Route::get('/patients/{patient}', [PengurusPatient::class, 'show'])
            ->name('patients.show');

        //Jadwal Dokter
        Route::resource('doctor_schedules',DoctorScheduleController::class);

        //Reservasi Pasien
        Route::resource('reservations',PengurusReservation::class);
        Route::delete(
            '/reservations/{reservation}/cancel',
            [\App\Http\Controllers\Pengurus\ReservationController::class, 'cancel']
        )->name('reservations.cancel');

        //Rekam Medis Pasien
        Route::resource('medical-records', MedicalRecordController::class)
            ->only(['index', 'create', 'store', 'show']);


     });


Route::middleware(['auth', 'role:pasien'])
    ->prefix('pasien')
    ->name('pasien.')
    ->group(function () {

        Route::get('/dashboard', [PatientDashboardController::class, 'index'])
            ->name('dashboard');
            
        Route::resource('reservations', \App\Http\Controllers\Pasien\ReservationController::class);
        Route::resource('medical-records', \App\Http\Controllers\Pasien\MedicalRecordController::class);

        Route::get('reservations/calendar/{doctor}', [\App\Http\Controllers\Pasien\ReservationController::class, 'calendar'])
            ->name('reservations.calendar');

        });



    //Atur Arah login sesuai role
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isPengurus()) {
            return redirect()->route('pengurus.dashboard');
        }

        if ($user->isPasien()) {
            return redirect()->route('pasien.dashboard');
        }

        abort(403);
    })->middleware(['auth'])->name('dashboard');


    //Profil
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

require __DIR__.'/auth.php';
