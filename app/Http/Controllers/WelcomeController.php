<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\DoctorDisplaySchedule;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // 1. Ambil Data News (dari NewsUpdateController)
        $news = News::latest()->take(3)->get();

        // 2. Persiapan Mapping Hari
        $dayMapping = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu',
        ];

        // 3. Ambil Data Jadwal Dokter (dari PublicDisplayController)
        $rawSchedules = DoctorDisplaySchedule::where('is_active', true)
            ->orderBy('doctor_name')
            ->get();

        // 4. Proses Grouping (Biar nama gak looping & jam diringkas)
        $schedules = $rawSchedules->groupBy('doctor_name')->map(function ($docs) use ($dayMapping) {
            return $docs->groupBy('specialty')->map(function ($times) use ($dayMapping) {
                return $times->groupBy(function ($item) {
                    return date('H:i', strtotime($item->start_time)) . ' - ' . date('H:i', strtotime($item->end_time));
                })->map(function ($days) use ($dayMapping) {
                    return $days->map(fn($d) => $dayMapping[strtolower($d->day)] ?? $d->day)
                                ->join(', ');
                });
            });
        });

        // 5. Kirim SEMUA data ke view welcome
        return view('welcome', compact('news', 'schedules'));
    }
}