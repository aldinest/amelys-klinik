<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Reservation;
use App\Models\Doctor;
use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\ReportExport;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $doctorId = $request->get('doctor_id', 'all');
        $doctors = Doctor::all();

        // Panggil private function biar logic-nya satu pintu
        $data = $this->getReportData($year, $doctorId); 

        return view('pengurus.report.index', array_merge([
            'year' => $year,
            'doctorId' => $doctorId,
            'doctors' => $doctors
        ], $data));
    }

    public function exportMonthlyPdf($month, $year, Request $request)
    {
        // Casting ke integer biar Carbon gak error 'string given'
        $monthInt = (int)$month;
        $yearInt = (int)$year;
        $doctorId = $request->get('doctor_id', 'all');

        // Ambil data detail pasien
        $query = MedicalRecord::with(['patient', 'doctor'])
            ->whereYear('examined_at', $yearInt)
            ->whereMonth('examined_at', $monthInt);

        if ($doctorId !== 'all') {
            $query->where('doctor_id', $doctorId);
        }

        $records = $query->with('patient')->orderBy('examined_at', 'asc')->get();
        
        // Ambil nama bulan dalam Bahasa Indonesia
        $monthName = Carbon::createFromDate($yearInt, $monthInt, 1)->translatedFormat('F');
        $doctorName = ($doctorId !== 'all') ? Doctor::find($doctorId)->name : 'Semua Dokter';

        // Buat PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pengurus.report.pdf_monthly', [
            'records' => $records,
            'monthName' => $monthName,
            'year' => $yearInt,
            'doctor' => $doctorName
        ]);

        // Set kertas A4 Potrait biar standar laporan
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream("Laporan-Amelys-$monthName-$yearInt.pdf");
    }

    public function exportPdf(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $doctorId = $request->get('doctor_id', 'all');
        
        // Pastikan memanggil private function yang kodenya sudah benar
        $data = $this->getReportData($year, $doctorId); 

        // SESUAIKAN NAMA VIEW: Pastikan filenya ada di resources/views/pengurus/report/pdf_yearly.blade.php
        $pdf = Pdf::loadView('pengurus.report.pdf_yearly', [
            'year' => $year,
            'data' => $data['monthlySummary'],
            'doctor' => $doctorId !== 'all' ? Doctor::find($doctorId)->name : 'Semua Dokter'
        ]);

        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream("Laporan-Tahunan-Amelys-$year.pdf");
    }

    public function exportExcel(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $doctorId = $request->get('doctor_id', 'all');

        return Excel::download(new ReportExport($year, $doctorId), "Report-Klinik-$year.xlsx");
    }

    private function getReportData($year, $doctorId)
    {
        $monthlySummary = [];
        $chartLabels = [];
        $chartDataReservations = [];
        $chartDataChecked = [];

        for ($m = 1; $m <= 12; $m++) {
            // Nama bulan Indonesia
            $monthName = \Illuminate\Support\Carbon::create()->month($m)->translatedFormat('F');
            
            // 1. Query Reservasi (Filter lewat relasi doctorSchedule)
            $resQuery = \App\Models\Reservation::whereHas('doctorSchedule', function($q) use ($year, $m) {
                $q->whereYear('schedule_date', $year)
                ->whereMonth('schedule_date', $m);
            });
            
            // 2. Query Medical Record (Selesai Periksa)
            $checkedQuery = \App\Models\MedicalRecord::whereYear('examined_at', $year)
                ->whereMonth('examined_at', $m);

            // Jika filter dokter dipilih
            if ($doctorId !== 'all') {
                // Reservasi difilter lewat tabel doctor_schedules karena di tabel reservations mungkin gak ada doctor_id langsung
                $resQuery->whereHas('doctorSchedule', function($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                });
                
                $checkedQuery->where('doctor_id', $doctorId);
            }

            $totalRes = $resQuery->count();
            $totalChecked = $checkedQuery->count();

            $monthlySummary[] = [
                'month_num' => $m,
                'month_name' => $monthName,
                'total_reservations' => $totalRes,
                'total_checked' => $totalChecked,
            ];

            $chartLabels[] = $monthName;
            $chartDataReservations[] = $totalRes;
            $chartDataChecked[] = $totalChecked;
        }

        return [
            'monthlySummary' => $monthlySummary,
            'chartLabels' => $chartLabels,
            'chartDataReservations' => $chartDataReservations,
            'chartDataChecked' => $chartDataChecked
        ];
    }

}