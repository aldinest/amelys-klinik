<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use App\Models\Doctor;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function calendar(Doctor $doctor)
    {
        $patientId = auth()->user()->patient->id;

        $schedules = $doctor->schedules()
            ->get() 
            ->map(function ($schedule) use ($doctor, $patientId) {
                // 1. HITUNG SISA KUOTA REALTIME
                // (Asumsi di tabel doctor_schedules ada kolom 'quota')
                $used = \App\Models\Reservation::where('doctor_schedule_id', $schedule->id)
                    ->where('status', '!=', 'cancelled')
                    ->count();
                $remaining = ($schedule->quota ?? 5) - $used;

                // 2. CEK APAKAH PASIEN INI SUDAH DAFTAR
                $hasRegistered = \App\Models\Reservation::where('doctor_schedule_id', $schedule->id)
                    ->where('patient_id', $patientId)
                    ->where('status', '!=', 'cancelled')
                    ->exists();

                return [
                    'id'    => $schedule->id,
                    'title' => date('H:i', strtotime($schedule->start_time)),
                    'start' => $schedule->schedule_date, 
                    'extendedProps' => [
                        'remaining' => $remaining, 
                        'doctor_name' => $doctor->name,
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

        $schedule = DoctorSchedule::findOrFail($request->doctor_schedule_id);
        $patient  = auth()->user()->patient;

        // 1. Cek kuota
        if ($schedule->remainingQuota() <= 0) {
            return back()->with('error', 'Kuota jadwal ini sudah penuh.');
        }

        // 2. Cegah double booking (tetap pertahankan)
        $exists = Reservation::where('doctor_schedule_id', $schedule->id)
            ->where('patient_id', $patient->id)
            ->whereIn('status', ['approved', 'pending', 'completed']) // Tambahkan 'pending' biar gak spam di jadwal yang sama
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kamu sudah memiliki reservasi di jadwal ini.');
        }

        // 3. Logika Approval: Cek apakah sudah pernah reservasi di bulan ini
        $hasMonthlyReservation = Reservation::where('patient_id', $patient->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', '!=', 'cancelled') // Jangan hitung yang batal
            ->exists();

        // Jika sudah pernah, statusnya 'pending', jika belum, 'approved'
        $status = $hasMonthlyReservation ? 'pending' : 'approved';

        // 4. Simpan Reservasi
        Reservation::create([
            'doctor_schedule_id' => $schedule->id,
            'patient_id' => $patient->id,
            'action' => $request->action,
            'status' => $status,
        ]);

        $message = $status === 'pending' 
            ? 'Reservasi berhasil dibuat dan sedang menunggu konfirmasi pengurus.' 
            : 'Reservasi berhasil dibuat.';

        return redirect()
            ->route('pasien.reservations.index')
            ->with('success', $message);
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
