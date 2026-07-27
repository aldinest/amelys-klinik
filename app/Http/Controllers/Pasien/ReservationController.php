<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\Doctor;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Notifications\ReservasiNotification;
use App\Notifications\ReservationCancelled;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ReservationController extends Controller
{

    public function index()
    {
        $patientId = Auth::user()->patient->id;

        $reservations = Reservation::with(['doctorSchedule.doctor'])
            ->where('patient_id', $patientId)
            ->latest()
            ->paginate(10); 

        return view('pasien.reservations.index', compact('reservations'));
    }

    public function create()
    {
        // Ambil SEMUA dokter tanpa filter status dulu untuk tes
        $doctors = \App\Models\Doctor::all(); 
        return view('pasien.reservations.create', compact('doctors'));
    }

    public function calendar($doctorId = null) 
    {
        // Pastikan user adalah pasien
        if (!auth()->user()->patient) {
            return response()->json(['error' => 'Data pasien tidak ditemukan'], 404);
        }

        $patientId = auth()->user()->patient->id;

        // Gunakan query builder agar fleksibel (bisa "semua" atau "per dokter")
        $schedules = \App\Models\DoctorSchedule::with('doctor')
            ->when($doctorId && $doctorId !== 'all', function ($query) use ($doctorId) {
                return $query->where('doctor_id', $doctorId);
            })
            ->get()
            ->map(function ($schedule) use ($patientId) {
                
                // CEK APAKAH PASIEN INI SUDAH DAFTAR
                // Gunakan scopeCountingQuota agar sinkron dengan aturan bisnis Anda
                $hasRegistered = $schedule->reservations()
                                        ->countingQuota()
                                        ->where('patient_id', $patientId)
                                        ->exists();

                return [
                    'id'    => $schedule->id,
                    'title' => date('H:i', strtotime($schedule->start_time)),
                    'start' => $schedule->schedule_date, 
                    'extendedProps' => [
                        // Gunakan atribut model yang sudah kita buat
                        'remaining'      => $schedule->remaining_quota, 
                        'doctor_name'    => optional($schedule->doctor)->name ?? 'Dokter Umum',
                        'has_registered' => $hasRegistered, 
                        'date_formatted' => date('d M Y', strtotime($schedule->schedule_date))
                    ]
                ];
            });

        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',
            'action' => 'required|string|max:1000',
        ]);

        return DB::transaction(function () use ($request) {
            $schedule = DoctorSchedule::lockForUpdate()->findOrFail($request->doctor_schedule_id);
            $patient  = auth()->user()->patient;

            // 1. Cek kuota
            if ($schedule->remaining_quota <= 0) {
                return back()->with('error', 'Kuota jadwal ini sudah penuh.');
            }

            // 2. Cegah double booking
            if (Reservation::where('doctor_schedule_id', $schedule->id)
                ->where('patient_id', $patient->id)
                ->countingQuota()->exists()) {
                return back()->with('error', 'Kamu sudah memiliki reservasi di jadwal ini.');
            }

            // 3. Logika status (Pending jika sudah ada reservasi bulan ini)
            $hasMonthlyReservation = Reservation::where('patient_id', $patient->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->countingQuota()
                ->exists();

            $status = $hasMonthlyReservation ? 'pending' : 'approved';

            // 4. Simpan Reservasi
            $reservation = Reservation::create([
                'doctor_schedule_id' => $schedule->id,
                'patient_id' => $patient->id,
                'action' => $request->action,
                'status' => $status,
            ]);

            // 5. Kirim Notifikasi ke Pengurus
            // Kita gunakan load agar relasi siap di toArray Notification
            $reservation->load(['patient', 'doctorSchedule.doctor']);
            
            $pengurus = User::where('role', 'pengurus')->get();
            Notification::send($pengurus, new ReservasiNotification($reservation));

            $message = $status === 'pending' 
                ? 'Reservasi berhasil dibuat dan sedang menunggu konfirmasi pengurus.' 
                : 'Reservasi berhasil dibuat.';

            return redirect()
                ->route('pasien.reservations.index')
                ->with('success', $message);
        });
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->patient_id != auth()->user()->patient->id) {
            abort(403);
        }

        $reservation->update([
            'status' => 'cancelled'
        ]);

        return redirect()
            ->route('pasien.reservations.index')
            ->with('success', 'Reservasi berhasil dibatalkan.');
    }

}
