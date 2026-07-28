<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
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
                'reservation.doctorSchedule.doctor',
                'patient',
                'doctor'
            ])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->whereHas('patient', function ($p) use ($search) {
                        $p->where('name', 'like', '%' . $search . '%')
                          ->orWhere('medical_record_number', 'like', '%' . $search . '%');
                    })->orWhereHas('doctor', function ($d) use ($search) {
                        $d->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('reservation.patient', function ($p) use ($search) {
                        $p->where('name', 'like', '%' . $search . '%')
                          ->orWhere('medical_record_number', 'like', '%' . $search . '%');
                    })->orWhereHas('reservation.doctorSchedule.doctor', function ($d) use ($search) {
                        $d->where('name', 'like', '%' . $search . '%');
                    });
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

        // Data tambahan untuk modal form tambah dadakan (walk-in) di view index
        $patients = Patient::orderBy('name', 'asc')->get();
        $doctorSchedules = DoctorSchedule::with('doctor')
            ->where('status', 'active')
            ->orderBy('schedule_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('pengurus.medical-records.index', compact('medicalRecords', 'patients', 'doctorSchedules'));
    }

    /**
     * Show form examination (create medical record)
     */
    public function create(Request $request)
    {
        $reservationId = $request->query('reservation_id');
        $reservation = null;

        if ($reservationId) {
            $reservation = Reservation::with([
                    'patient.medicalRecords.doctor',
                    'doctorSchedule.doctor',
                    'medicalRecord'
                ])
                ->where('id', $reservationId)
                ->where('status', 'approved')
                ->firstOrFail();

            // Proteksi: Jika RM untuk reservasi ini sudah ada, tendang balik
            if ($reservation->medicalRecord) {
                return redirect()
                    ->route('pengurus.reservations.show', $reservation->doctor_schedule_id)
                    ->with('error', 'Rekam medis untuk kunjungan ini sudah ada.');
            }
        }

        // Ambil data pasien dan jadwal dokter untuk mode walk-in / pilihan manual
        $patients = Patient::all();
        $doctorSchedules = \App\Models\DoctorSchedule::with('doctor')->get(); // Sesuaikan relasi jadwal dokter Anda

        // Generate nomor RM otomatis
        $latest = MedicalRecord::latest('id')->first();
        $nextNumber = $latest ? intval(substr($latest->no_rm, -6)) + 1 : 1;
        $noRm = 'RM-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        return view('pengurus.medical-records.create', compact('reservation', 'patients', 'doctorSchedules', 'noRm'));
    }
    /**
     * Store medical record (Support for standard reservation & emergency walk-in)
     */
    public function store(Request $request)
    {
        $request->validate([
            'reservation_id'      => 'nullable|exists:reservations,id',
            'patient_id'          => 'required_without:reservation_id|nullable|exists:patients,id',
            'doctor_id'           => 'nullable|exists:doctors,id',
            'doctor_schedule_id'  => 'required_without:reservation_id|nullable|exists:doctor_schedules,id',
            'complaint'           => 'required|string',
            'diagnosis'           => 'required|string',
            'treatment'           => 'required|string',
            'doctor_notes'        => 'nullable|string',
        ]);

        // Skenario 1: Pasien Dadakan / Walk-in (Tanpa Reservasi)
        if (!$request->filled('reservation_id')) {
            $patient = Patient::findOrFail($request->patient_id);
            $schedule = DoctorSchedule::findOrFail($request->doctor_schedule_id);

            // Generate RM Number jika pasien belum punya nomor RM
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
                'reservation_id'      => null,
                'patient_id'          => $patient->id,
                'doctor_id'           => $schedule->doctor_id,
                'doctor_schedule_id'  => $schedule->id,
                'complaint'           => $request->complaint,
                'diagnosis'           => $request->diagnosis,
                'treatment'           => $request->treatment,
                'doctor_notes'        => $request->doctor_notes,
                'examined_at'         => $schedule->schedule_date,
            ]);

            return redirect()
                ->route('pengurus.medical-records.index')
                ->with('success', 'Rekam medis pasien dadakan berhasil disimpan.');
        }

        // Skenario 2: Pasien Normal via Reservasi
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
            'reservation_id'      => $reservation->id,
            'patient_id'          => $reservation->patient_id,
            'doctor_id'           => $reservation->doctorSchedule->doctor_id,
            'doctor_schedule_id'  => $reservation->doctor_schedule_id,
            'complaint'           => $request->complaint,
            'diagnosis'           => $request->diagnosis,
            'treatment'           => $request->treatment,
            'doctor_notes'        => $request->doctor_notes,
            'examined_at'         => $reservation->doctorSchedule->schedule_date,
        ]);

        $reservation->update(['status' => 'completed']);

        return redirect()
            ->route('pengurus.reservations.show', $reservation->doctor_schedule_id)
            ->with('success', 'Rekam medis berhasil disimpan');
    }

    /**
     * Show medical record detail
     */
    public function show(MedicalRecord $medicalRecord)
    {
        // 1. Load relasi utama agar informasi pasien dan dokter muncul (baik lewat reservasi maupun langsung)
        $medicalRecord->load(['reservation.patient', 'reservation.doctorSchedule.doctor', 'patient', 'doctor']);
        
        // Tentukan ID dokter yang memeriksa (bisa dari relasi reservasi atau langsung dari kolom doctor_id)
        $doctorId = $medicalRecord->doctor_id ?? optional(optional($medicalRecord->reservation)->doctorSchedule)->doctor_id;

        // 2. Ambil history pasien yang ditangani oleh dokter tersebut
        $history = MedicalRecord::where('patient_id', $medicalRecord->patient_id)
                    ->where(function($q) use ($doctorId) {
                        $q->where('doctor_id', $doctorId)
                          ->orWhereHas('reservation.doctorSchedule', function($query) use ($doctorId) {
                              $query->where('doctor_id', $doctorId);
                          });
                    })
                    ->with(['reservation.doctorSchedule.doctor', 'doctor'])
                    ->latest()
                    ->get();

        return view('pengurus.medical-records.show', compact('medicalRecord', 'history'));
    }

    /**
     * Menampilkan form edit rekam medis
     */
    public function edit($id)
    {
        // Load relasi agar data pasien tampil di header form edit
        $medicalRecord = MedicalRecord::with(['reservation.patient', 'patient'])->findOrFail($id);
        
        return view('pengurus.medical-records.edit', compact('medicalRecord'));
    }

    /**
     * Memperbarui data rekam medis ke database
     */
    public function update(Request $request, $id)
    {
        // Validasi input sesuai dengan field yang ada di blade
        $request->validate([
            'complaint' => 'required|string',
            'diagnosis' => 'required|string|max:255',
            'treatment' => 'required|string|max:255',
            'doctor_notes' => 'nullable|string',
        ]);

        $medicalRecord = MedicalRecord::findOrFail($id);

        // Update data rekam medis
        $medicalRecord->update([
            'complaint' => $request->complaint,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'doctor_notes' => $request->doctor_notes,
        ]);

        // Redirect kembali ke halaman detail dengan pesan sukses
        return redirect()
            ->route('pengurus.medical-records.show', $id)
            ->with('success', 'Data rekam medis berhasil diperbarui.');
    }

    public function cetak($id) {
        $medicalRecord = MedicalRecord::with(['patient', 'doctor', 'reservation.patient'])->findOrFail($id);
        
        $patientId = $medicalRecord->patient_id ?? optional($medicalRecord->reservation)->patient_id;

        $history = MedicalRecord::where('patient_id', $patientId)
            ->orWhereHas('reservation', function($q) use ($patientId) {
                $q->where('patient_id', $patientId);
            })->get();

        return view('pengurus.medical-records.print_view', compact('medicalRecord', 'history'));
    }
}