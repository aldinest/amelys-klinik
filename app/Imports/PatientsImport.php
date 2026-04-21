<?php

namespace App\Imports;

use App\Models\Patient;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

HeadingRowFormatter::default('slug');

class PatientsImport implements ToModel, WithHeadingRow
{
    public array $duplicates = []; 

    public function model(array $row)
    {
        if (empty($row['nama'])) return null;

        $dob = $this->parseDate($row['tanggal_lahir'] ?? null);
        $email = isset($row['email']) ? trim(strtolower($row['email'])) : '';

        // 1. Logika Pembuatan User (Akun Login)
        $user = null;
        if (!empty($email) && $email !== '-' && str_contains($email, '@')) {
            // Gunakan updateOrCreate supaya nggak bentrok
            $user = User::updateOrCreate(
                ['email' => $email], // Cari berdasarkan email
                [
                    'name'     => trim($row['nama']),
                    'password' => Hash::make(strtolower(explode(' ', $row['nama'])[0]) . ($dob ? Carbon::parse($dob)->format('dmY') : '123456')),
                    'role'     => 'pasien',
                ]
            );
        }

        // 2. Logika Simpan/Update Pasien
        // Kita cari datanya, kalau ada kita update user_id-nya, kalau nggak ada kita buat baru.
        return Patient::updateOrCreate(
            [
                'name'  => trim($row['nama']),
                'phone' => $row['no_hp'] ?? '-',
            ],
            [
                'address'       => $row['alamat'] ?? 'Unknown',
                'gender'        => str_contains(strtolower($row['gender'] ?? ''), 'perem') ? 'P' : 'L',
                'date_of_birth' => $dob,
                'user_id'       => $user ? $user->id : null,
                'medical_record_number' => $row['rekam_medis'] ?? null,
            ]
        );
    }

    private function parseDate($value)
    {
        if (!$value) return null;
        try {
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}