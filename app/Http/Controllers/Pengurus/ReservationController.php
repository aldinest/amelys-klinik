<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\DoctorSchedule;
use App\Models\Doctor; 
use App\Models\Patient;
use App\Models\User;
use App\Notifications\ReservationCancelled;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ReservationsExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $doctors = Doctor::orderBy('name', 'asc')->get();

        // 1. Inisialisasi Query dasar dengan withCount
        $query = DoctorSchedule::query()->with(['doctor'])
            ->withCount(['reservations as booked' => function($q) {
                $q->countingQuota();
            }]);

        // 2. Filter Pasien (Gunakan if tunggal, jangan overwrite $query)
        if ($request->filled('search_patient')) {
            $query->whereHas('reservations.patient', function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search_patient . '%');
            });
        }

        // 3. Filter Tanggal (Dari & Sampai) - JANGAN tulis ulang $query = ...
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('schedule_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('schedule_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('schedule_date', '<=', $request->end_date);
        }

        // 4. Filter Dokter
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // 5. Filter Status
        if ($request->filled('status')) {
            $today = Carbon::today();
            if ($request->status == 'selesai') {
                $query->whereDate('schedule_date', '<', $today);
            } elseif ($request->status == 'penuh') {
                $query->havingRaw('booked >= quota');
            } elseif ($request->status == 'tersedia') {
                $query->whereDate('schedule_date', '>=', $today)
                    ->havingRaw('booked < quota');
            }
        }

        // Eksekusi
        $schedules = $query->orderBy('schedule_date', 'desc')
                        ->paginate(10)
                        ->withQueryString();

        return view('pengurus.reservations.index', compact('schedules', 'doctors'));
    }
    
    public function create(Request $request)
    {
        $schedule = DoctorSchedule::findOrFail($request->schedule);

        // Gunakan atribut remaining_quota
        if ($schedule->remaining_quota <= 0) {
            return redirect()->back()->with('error', 'Jadwal ini sudah penuh!');
        }

        // Gunakan scopeCountingQuota untuk mengambil pasien yang belum terdaftar
        $patients = Patient::whereDoesntHave('reservations', function ($q) use ($schedule) {
                $q->where('doctor_schedule_id', $schedule->id)->countingQuota();
            })
            ->orderBy('name')
            ->get();

        return view('pengurus.reservations.create', compact('schedule', 'patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_schedule_id' => 'required|exists:doctor_schedules,id',
            'patient_id'         => 'required|exists:patients,id',
            'action'             => 'required|string|max:100',
        ]);

        $schedule = DoctorSchedule::findOrFail($request->doctor_schedule_id);

        // Gunakan atribut remaining_quota
        if ($schedule->remaining_quota <= 0) {
            return back()->with('error', 'Kuota jadwal sudah penuh.');
        }

        // Gunakan scopeCountingQuota
        $exists = Reservation::where('doctor_schedule_id', $schedule->id)
            ->where('patient_id', $request->patient_id)
            ->countingQuota()
            ->exists();

        if ($exists) {
            return back()->with('error', 'Pasien ini sudah terdaftar di jadwal ini.');
        }

        Reservation::create([
            'doctor_schedule_id' => $schedule->id,
            'patient_id'         => $request->patient_id,
            'status'             => 'approved',
            'action'             => $request->action,
        ]);

        return redirect()->route('pengurus.reservations.show', $schedule->id)
                         ->with('success', 'Reservasi berhasil ditambahkan');
    }

    public function show($doctorScheduleId)
    {
        $schedule = DoctorSchedule::with(['doctor', 'reservations.patient'])->findOrFail($doctorScheduleId);
        
        // Kelompokkan reservasi berdasarkan status untuk ditampilkan di tab-tab
        $reservations = $schedule->reservations;
        
        $data = [
            'approved' => $reservations->where('status', 'approved'),
            'completed' => $reservations->where('status', 'completed'),
            'pending' => $reservations->where('status', 'pending'),
            'cancelled' => $reservations->where('status', 'cancelled'),
        ];

        // Hitung kuota yang terpakai (disetujui + pending)
        $usedQuota = $reservations->whereIn('status', ['approved', 'completed', 'pending'])->count();

        return view('pengurus.reservations.show', compact('schedule', 'data', 'usedQuota', 'reservations'));
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->status === 'completed') {
            return back()->with('error', 'Data sudah selesai diperiksa, tidak bisa dibatalkan');
        }

        $reservation->update(['status' => 'cancelled']);
        return back()->with('success', 'Reservasi berhasil dibatalkan');
    }

    public function exportPdf($scheduleId)
    {
        // 1. Ambil data jadwal beserta dokternya
        $schedule = DoctorSchedule::with('doctor')->findOrFail($scheduleId);

        // 2. Ambil data reservasi yang ada di jadwal tersebut
        // Kita filter yang statusnya 'approved' atau 'completed' saja (opsional)
        $reservations = Reservation::with('patient')
                // Sesuaikan kolomnya di sini juga
                ->where('doctor_schedule_id', $scheduleId) 
                ->orderBy('created_at', 'asc')
                ->get();

        // 3. Load view khusus untuk layout PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pengurus.reservations.pdf-print', compact('schedule', 'reservations'))
              ->setPaper('a4', 'portrait');

        // 4. Download file dengan nama yang rapi
        $fileName = 'Laporan_Reservasi_' . $schedule->schedule_date . '.pdf';
        return $pdf->stream($fileName); // Pakai stream biar bisa preview dulu di browser
    }

    public function exportExcel($scheduleId)
    {
        $schedule = DoctorSchedule::findOrFail($scheduleId);
        $namaFile = 'Data_Pasien_' . $schedule->schedule_date . '.xlsx';

        return Excel::download(new ReservationsExport($scheduleId), $namaFile);
    }

    // Pastikan method ini ada!
    public function indexPending()
    {
        $pendingReservations = Reservation::where('status', 'pending')->latest()->get();
        return view('pengurus.reservations.pending', compact('pendingReservations'));
    }

    // Pastikan method approve juga ada
    public function approve($id)
    {
        $res = Reservation::findOrFail($id);
        $schedule = $res->doctorSchedule;

        // Cek kuota lagi sebelum konfirmasi
        $currentBooked = $schedule->reservations()->whereIn('status', ['approved', 'completed'])->count();
        
        if ($currentBooked >= $schedule->quota) {
            return back()->with('error', 'Tidak bisa menyetujui, kuota sudah penuh!');
        }

        $res->update(['status' => 'approved']);
        return back()->with('success', 'Reservasi berhasil disetujui.');
    }

    public function reject($id)
    {
        $res = Reservation::findOrFail($id);
        $res->update(['status' => 'cancelled']); 

        // Menggunakan relasi yang baru saja dibuat
        $pasien = $res->user; 

        if ($pasien) {
            $details = [
                'tanggal' => \Carbon\Carbon::parse($res->tanggal_reservasi)->format('d-m-Y'),
            ];
            
            $pasien->notify(new \App\Notifications\ReservationCancelled($details));
        }

        return back()->with('success', 'Reservasi telah ditolak.');
    }

}