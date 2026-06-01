<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\DoctorSchedule;
use App\Models\Doctor; 
use App\Models\Patient;
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

        // 1. Inisialisasi Query
        $query = DoctorSchedule::query()->with(['doctor', 'reservations']);

        // 2. Filter Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            // Jika dua-duanya diisi, cari di antara tanggal tersebut
            $query->whereBetween('schedule_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            // Jika CUMA start_date yang diisi, cari yang sama persis (Exact Match)
            $query->whereDate('schedule_date', $request->start_date);
        } elseif ($request->filled('end_date')) {
            // Jika CUMA end_date yang diisi, cari yang sama persis juga
            $query->whereDate('schedule_date', $request->end_date);
        }

        // 3. Filter Dokter
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // 4. Filter Status (Sinkron dengan Badge Selesai/Penuh/Tersedia)
        if ($request->filled('status')) {
            $today = \Carbon\Carbon::today();
            $query->withCount(['reservations as booked' => function($q) {
                $q->whereIn('status', ['approved', 'completed']);
            }]);

            if ($request->status == 'selesai') {
                $query->whereDate('schedule_date', '<', $today);
            } elseif ($request->status == 'penuh') {
                $query->havingRaw('booked >= quota');
            } elseif ($request->status == 'tersedia') {
                $query->whereDate('schedule_date', '>=', $today)
                    ->havingRaw('booked < quota');
            }
        }

        // DEBUG DISINI UNTUK LIHAT HASIL AKHIR
        // dd($query->toSql(), $query->getBindings()); 

        $schedules = $query->orderBy('schedule_date', 'desc')
                        ->paginate(10)
                        ->withQueryString();

        return view('pengurus.reservations.index', compact('schedules', 'doctors'));
    }
    
    public function create(Request $request)
    {
        $schedule = DoctorSchedule::findOrFail($request->schedule);

        // Validasi Expired
        if (Carbon::parse($schedule->schedule_date)->isPast() && !Carbon::parse($schedule->schedule_date)->isToday()) {
            return redirect()->route('pengurus.reservations.index')
                             ->with('error', 'Tidak dapat menambah pasien. Jadwal ini sudah berakhir.');
        }

        $usedQuota = $schedule->reservations()
            ->whereIn('status', ['approved', 'completed'])
            ->count();

        if ($usedQuota >= $schedule->quota) {
            return redirect()->back()->with('error', 'Jadwal ini sudah penuh!');
        }

        // Ambil pasien yang belum daftar di jadwal ini
        $patients = Patient::whereDoesntHave('reservations', function ($q) use ($schedule) {
                $q->where('doctor_schedule_id', $schedule->id)
                  ->whereIn('status', ['approved', 'completed']);
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

        // Security Check Expired
        if (Carbon::parse($schedule->schedule_date)->isPast() && !Carbon::parse($schedule->schedule_date)->isToday()) {
            return redirect()->route('pengurus.reservations.index')->with('error', 'Gagal. Jadwal sudah expired.');
        }

        // Validasi Kuota
        $usedQuota = $schedule->reservations()->whereIn('status', ['approved', 'completed'])->count();
        if ($usedQuota >= $schedule->quota) {
            return back()->with('error', 'Kuota jadwal sudah penuh.');
        }

        // Cek Double Entry
        $exists = Reservation::where('doctor_schedule_id', $schedule->id)
            ->where('patient_id', $request->patient_id)
            ->whereIn('status', ['approved', 'completed'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Pasien ini sudah terdaftar hari ini.');
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

    public function show($doctorScheduleId)
    {
        $schedule = DoctorSchedule::with([
            'doctor',
            'reservations.patient',
            'reservations.medicalRecord'
        ])->findOrFail($doctorScheduleId);

        $usedQuota = $schedule->reservations()->whereIn('status', ['approved', 'completed'])->count();
        $reservations = $schedule->reservations;

        return view('pengurus.reservations.show', compact('schedule', 'reservations', 'usedQuota'));
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->status === 'completed') {
            return back()->with('error', 'Data sudah selesai diperiksa, tidak bisa dihapus');
        }

        $reservation->delete();
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
        $res->update(['status' => 'approved']);
        return back()->with('success', 'Reservasi pasien telah disetujui.');
    }

    public function reject($id)
    {
        $res = Reservation::findOrFail($id);
        // Kita bisa hapus reservasi atau ubah statusnya jadi 'cancelled'
        $res->update(['status' => 'cancelled']); 
        
        return back()->with('success', 'Reservasi pasien telah ditolak.');
    }

}