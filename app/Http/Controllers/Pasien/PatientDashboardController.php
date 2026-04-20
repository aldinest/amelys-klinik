<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\MedicalRecord;
use App\Models\DoctorSchedule;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patient = $user->patient;

        // Jika user bukan pasien atau data pasien tidak ditemukan, amankan sistem
        if (!$patient) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Data profil pasien tidak ditemukan.');
        }

        /**
         * Jadwal Dokter HARI INI saja
         * Kita load relasi 'doctor' dan 'reservations' 
         * agar perhitungan kuota di Blade jadi ringan dan cepat
         */
        $todaySchedules = DoctorSchedule::with(['doctor', 'reservations'])
            ->whereDate('schedule_date', Carbon::today())
            ->where('status', 'active') 
            ->orderBy('start_time', 'asc')
            ->get();

        // Ambil 3 Berita Terbaru
        $news = News::latest()->take(3)->get();

        /**
         * Cek apakah pasien sudah memiliki reservasi aktif hari ini
         * Ini berguna jika kamu ingin membatasi 1 pasien hanya boleh 1 daftar per hari
         */
        $activeReservation = Reservation::where('patient_id', $patient->id)
            ->whereHas('doctorSchedule', function($q) {
                $q->whereDate('schedule_date', Carbon::today());
            })
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        // Total riwayat berobat (Rekam Medis)
        $totalMedicalRecords = MedicalRecord::where('patient_id', $patient->id)->count();

        return view('pasien.dashboard', compact(
            'patient',
            'activeReservation',
            'todaySchedules',
            'totalMedicalRecords',
            'news'
        ));
    }
}