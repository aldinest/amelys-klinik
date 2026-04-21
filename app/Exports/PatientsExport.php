<?php

namespace App\Exports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PatientsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil data pasien beserta relasi user-nya untuk dapat Email
        return Patient::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',         // Email diambil dari akun user
            'Alamat',
            'No HP',
            'Gender',
            'Tanggal Lahir',
            'Rekam Medis'
        ];
    }

    /**
    * @param mixed $patient
    */
    public function map($patient): array
    {
        return [
            $patient->name,
            // Jika pasien punya user (punya akun), tampilkan emailnya
            $patient->user ? $patient->user->email : '-', 
            $patient->address,
            $patient->phone,
            $patient->gender == 'L' ? 'Laki-laki' : 'Perempuan',
            // Format Y-m-d paling aman buat di-import balik nanti
            $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('Y-m-d') : '-',
            // Bersihkan tag HTML jika ada di field rekam medis
            strip_tags($patient->medical_record_number),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header jadi Bold dan background abu-abu tipis
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0'],
                ],
            ],
        ];
    }
}