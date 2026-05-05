<?php

namespace App\Exports;

use App\Models\Reservation;
use App\Models\DoctorSchedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class ReservationsExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithCustomStartCell
{
    protected $scheduleId;
    protected $schedule;

    public function __construct($scheduleId)
    {
        $this->scheduleId = $scheduleId;
        // Ambil data jadwal untuk detail header
        $this->schedule = DoctorSchedule::with('doctor')->findOrFail($scheduleId);
        
        // Memastikan Carbon menggunakan bahasa Indonesia
        Carbon::setLocale('id');
    }

    public function startCell(): string
    {
        return 'A8';
    }

    public function collection()
    {
        return Reservation::with('patient')
            ->where('doctor_schedule_id', $this->scheduleId)
            ->whereIn('status', ['approved', 'completed'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA PASIEN',
            'NO. REKAM MEDIS',
            'TINDAKAN',
            'STATUS',
            'WAKTU DAFTAR'
        ];
    }

    public function map($reservation): array
    {
        static $no = 1;

        // Logika Status Bahasa Indonesia
        $statusIndo = '';
        switch ($reservation->status) {
            case 'approved':
                $statusIndo = 'Disetujui';
                break;
            case 'completed':
                $statusIndo = 'Selesai';
                break;
            default:
                $statusIndo = 'Dibatalkan';
                break;
        }

        return [
            $no++,
            strtoupper($reservation->patient->name),
            $reservation->patient->medical_record_number ?? '-',
            $reservation->action ?? '-',
            $statusIndo, // Sudah bahasa Indonesia
            $reservation->created_at->translatedFormat('d/m/Y H:i') // Tanggal Indo
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $dataCount = $this->collection()->count();

                // Judul
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'LAPORAN DAFTAR PASIEN RESERVASI');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

                // Informasi Detail (Pakai Bahasa Indonesia)
                $sheet->setCellValue('A3', 'Nama Dokter');
                $sheet->setCellValue('B3', ': ' . $this->schedule->doctor->name);
                
                $sheet->setCellValue('A4', 'Tanggal Praktik');
                $sheet->setCellValue('B4', ': ' . Carbon::parse($this->schedule->schedule_date)->translatedFormat('l, d F Y'));

                $sheet->setCellValue('A5', 'Jam Praktik');
                $sheet->setCellValue('B5', ': ' . substr($this->schedule->start_time, 0, 5) . ' - ' . substr($this->schedule->end_time, 0, 5) . ' WIB');

                $sheet->setCellValue('A6', 'Total Pasien');
                $sheet->setCellValue('B6', ': ' . $dataCount . ' Orang');

                // Styling Header Tabel
                $headerRange = 'A8:F8';
                $sheet->getStyle($headerRange)->getFont()->setBold(true);
                $sheet->getStyle($headerRange)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('F2F2F2');

                // Auto Size Kolom
                foreach (range('A', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Border Tabel
                $stopRow = 8 + $dataCount;
                $sheet->getStyle("A8:F{$stopRow}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}