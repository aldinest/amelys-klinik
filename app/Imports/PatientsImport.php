<?php

namespace App\Imports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

HeadingRowFormatter::default('slug');

class PatientsImport implements ToModel, WithHeadingRow
{
    public array $duplicates = []; 

    public function model(array $row)
    {
        
        if (empty($row['nama'])) {
            return null;
        }

        // Memanggil method helper parseDate di bawah
        $dob = $this->parseDate($row['tanggal_lahir'] ?? null);

        $rawGender = strtolower(trim($row['gender'] ?? ''));
        $gender = str_contains($rawGender, 'perem') ? 'P' : 'L';

        // 2. Cek Double pakai key 'nama' dan 'no_hp'
        $exists = Patient::where('name', trim($row['nama']))
            ->where('phone', $row['no_hp'])
            ->where('date_of_birth', $dob)
            ->exists();

        if ($exists) {
            $this->duplicates[] = $row['nama'] . ' (' . $row['no_hp'] . ')';
            return null; 
        }

        // 3. Simpan ke database
        return new Patient([
            'name'          => trim($row['nama']),
            'address'       => $row['alamat'] ?? 'Unknown',
            'phone'         => $row['no_hp'],
            'gender'        => $gender,
            'date_of_birth' => $dob,
            'user_id'       => null, 
        ]);
    }

    /**
     * INI METHOD YANG TADI HILANG/UNDEFINED
     * Pastikan letaknya di DALAM class (sebelum kurung kurawal penutup terakhir)
     */
    private function parseDate($value)
    {
        if (!$value) return null;

        try {
            // Jika formatnya angka (serial Excel)
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            // Jika formatnya string biasa
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}