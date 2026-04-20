<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        // Ambil ID pasien yang sedang login
        $patientId = Auth::user()->patient->id;

        $query = MedicalRecord::with(['doctor', 'reservation'])
            ->where('patient_id', $patientId)
            ->latest();

        // Fitur Search sederhana (cari berdasarkan diagnosa atau nama dokter)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('diagnosis', 'like', "%{$search}%")
                  ->orWhereHas('doctor', function($dq) use ($search) {
                      $dq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $medicalRecords = $query->paginate(10);

        return view('pasien.medical-records.index', compact('medicalRecords'));
    }

    public function show($id)
    {
        // Pastikan pasien hanya bisa melihat RM miliknya sendiri (Security Check)
        $patientId = Auth::user()->patient->id;

        $record = MedicalRecord::with(['patient', 'doctor', 'reservation'])
            ->where('patient_id', $patientId)
            ->findOrFail($id);

        return view('pasien.medical-records.show', compact('record'));
    }
}
