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
        return Patient::select(
            'name',
            'address',
            'phone',
            'gender',
            'date_of_birth',
            'medical_record_number'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Alamat',
            'No HP',
            'Gender',
            'Tanggal Lahir',
            'Rekam Medis'
        ];
    }

    public function map($patient): array
    {
        return [
            $patient->name,
            $patient->address,
            $patient->phone,
            $patient->gender == 'L' ? 'Laki-laki' : 'Perempuan',
            \Carbon\Carbon::parse($patient->date_of_birth)->format('d-m-Y'),
            strip_tags($patient->medical_record_number), // biar gak ada HTML aneh
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // header jadi bold
        ];
    }
}
