<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon; // Pastikan Carbon di-import

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $schedules = DoctorSchedule::with(['doctor'])
            ->when($request->date, function ($q) use ($request) {
                $q->whereDate('schedule_date', $request->date);
            })
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('doctor', function ($d) use ($request) {
                    $d->where('name', 'like', '%' . $request->search . '%');
                });
            })
            ->orderBy('schedule_date', 'desc')
            ->paginate(10);

        return view('pengurus.reservations.index', compact('schedules'));
    }

    /**
     * FORM TAMBAH RESERVASI
     */
    public function create(Request $request)
    {
        $schedule = DoctorSchedule::findOrFail($request->schedule);

        // --- VALIDASI TANGGAL EXPIRED ---
        // Jika tanggal sudah lewat DAN bukan hari ini
        if (Carbon::parse($schedule->schedule_date)->isPast() && !Carbon::parse($schedule->schedule_date)->isToday()) {
            return redirect()->route('pengurus.reservations.index')
                             ->with('error', 'Tidak dapat menambah pasien. Jadwal ini sudah berakhir/lewat.');
        }

        // Hitung kuota terpakai
        $usedQuota = $schedule->reservations()
            ->whereIn('status', ['approved', 'completed'])
            ->count();

        // Validasi kuota penuh
        if ($usedQuota >= $schedule->quota) {
            return redirect()->back()->with('error', 'Jadwal ini sudah penuh!');
        }

        $patients = Patient::whereDoesntHave('reservations', function ($q) use ($schedule) {
                $q->where('doctor_schedule_id', $schedule->id)
                  ->whereIn('status', ['approved', 'completed']);
            })
            ->orderBy('name')
            ->get();

        return view('pengurus.reservations.create', compact('schedule', 'patients'));
    }

    /**
     * SIMPAN RESERVASI
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',
            'patient_id'         => 'required|exists:patients,id',
            'action'             => 'required|string|max:100',
        ]);

        $schedule = DoctorSchedule::findOrFail($request->doctor_schedule_id);

        // --- VALIDASI TANGGAL EXPIRED (SECURITY CHECK) ---
        if (Carbon::parse($schedule->schedule_date)->isPast() && !Carbon::parse($schedule->schedule_date)->isToday()) {
            return redirect()->route('pengurus.reservations.index')
                             ->with('error', 'Gagal menyimpan. Jadwal sudah expired.');
        }

        // Validasi Kuota
        $usedQuota = $schedule->reservations()
            ->whereIn('status', ['approved', 'completed'])
            ->count();

        if ($usedQuota >= $schedule->quota) {
            return back()->with('error', 'Kuota jadwal sudah penuh.');
        }

        // Cegah pasien dobel daftar
        $exists = Reservation::where('doctor_schedule_id', $schedule->id)
            ->where('patient_id', $request->patient_id)
            ->whereIn('status', ['approved', 'completed'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Pasien ini sudah terdaftar dalam kuota hari ini');
        }

        Reservation::create([
            'doctor_schedule_id' => $schedule->id,
            'patient_id'         => $request->patient_id,
            'status'             => 'approved',
            'action'             => $request->action,
        ]);

        return redirect()
            ->route('pengurus.reservations.show', $schedule->id)
            ->with('success', 'Reservasi berhasil ditambahkan');
    }

    /**
     * DETAIL JADWAL + LIST RESERVASI
     */
    public function show($doctorScheduleId)
    {
        $schedule = DoctorSchedule::with([
            'doctor',
            'reservations.patient',
            'reservations.medicalRecord'
        ])->findOrFail($doctorScheduleId);

        $usedQuota = $schedule->reservations()
            ->whereIn('status', ['approved', 'completed'])
            ->count();

        $reservations = $schedule->reservations;

        return view('pengurus.reservations.show', compact('schedule', 'reservations', 'usedQuota'));
    }

    /**
     * HAPUS LANGSUNG (CANCEL)
     */
    public function cancel(Reservation $reservation)
    {
        if ($reservation->status === 'completed') {
            return back()->with('error', 'Pasien sudah selesai diperiksa, data tidak bisa dihapus');
        }

        $reservation->delete();
        return back()->with('success', 'Reservasi berhasil dibatalkan');
    }
}