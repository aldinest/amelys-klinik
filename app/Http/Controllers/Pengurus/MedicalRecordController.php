<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MedicalRecordController extends Controller
{
    /**
     * List medical records (Unique by Patient)
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $medicalRecords = MedicalRecord::with([
                'reservation.patient',
                'reservation.doctorSchedule.doctor'
            ])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('reservation.patient', function ($p) use ($search) {
                    $p->where('name', 'like', '%' . $search . '%')
                      ->orWhere('medical_record_number', 'like', '%' . $search . '%');
                })->orWhereHas('reservation.doctorSchedule.doctor', function ($d) use ($search) {
                    $d->where('name', 'like', '%' . $search . '%');
                });
            })
            // Mengambil ID terakhir dari medical record untuk tiap pasien agar tidak double
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                      ->from('medical_records')
                      ->groupBy('patient_id');
            })
            ->latest()
            ->paginate(10);

        return view('pengurus.medical-records.index', compact('medicalRecords'));
    }

    /**
     * Show form examination (create medical record)
     */
    public function create(Request $request)
    {
        $reservationId = $request->query('reservation_id');
        abort_if(!$reservationId, 404);

        $reservation = Reservation::with([
                'patient',
                'doctorSchedule.doctor',
                'medicalRecord'
            ])
            ->where('id', $reservationId)
            ->where('status', 'approved')
            ->firstOrFail();

        if ($reservation->medicalRecord) {
            return redirect()
                ->route('pengurus.reservations.show', $reservation->doctor_schedule_id)
                ->with('error', 'Rekam medis pasien ini sudah ada');
        }

        return view('pengurus.medical-records.create', compact('reservation'));
    }

    /**
     * Store medical record
     */
    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'complaint'      => 'required|string',
            'diagnosis'      => 'required|string',
            'treatment'      => 'required|string',
            'doctor_notes'   => 'nullable|string',
        ]);

        $reservation = Reservation::with('patient')
            ->where('id', $request->reservation_id)
            ->where('status', 'approved')
            ->firstOrFail();

        $patient = $reservation->patient;

        // Generate RM Number jika belum ada
        if (!$patient->medical_record_number) {
            $lastRM = Patient::whereNotNull('medical_record_number')
                ->orderBy('medical_record_number', 'desc')
                ->value('medical_record_number');

            $nextNumber = $lastRM ? ((int) str_replace('RM-', '', $lastRM)) + 1 : 1;
            $newRM = 'RM-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            while (Patient::where('medical_record_number', $newRM)->exists()) {
                $nextNumber++;
                $newRM = 'RM-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            }

            $patient->update(['medical_record_number' => $newRM]);
        }

        MedicalRecord::create([
            'reservation_id' => $reservation->id,
            'patient_id'     => $reservation->patient_id,
            'doctor_id'      => $reservation->doctorSchedule->doctor_id, 
            'complaint'      => $request->complaint,
            'diagnosis'      => $request->diagnosis,
            'treatment'      => $request->treatment,
            'doctor_notes'   => $request->doctor_notes,
            'examined_at'    => now(), 
        ]);

        $reservation->update(['status' => 'completed']);

        return redirect()
            ->route('pengurus.medical-records.index')
            ->with('success', 'Rekam medis berhasil disimpan');
    }

    /**
     * Show medical record detail
     */
    public function show(MedicalRecord $medicalRecord)
    {
        // Load riwayat rekam medis lainnya untuk pasien yang sama jika diperlukan di view show
        $medicalRecord->load(['reservation.patient', 'reservation.doctorSchedule.doctor']);
        
        // Ambil semua history pasien tersebut untuk ditampilkan di halaman detail
        $history = MedicalRecord::where('patient_id', $medicalRecord->patient_id)
                    ->with('reservation.doctorSchedule.doctor')
                    ->latest()
                    ->get();

        return view('pengurus.medical-records.show', compact('medicalRecord', 'history'));
    }
}