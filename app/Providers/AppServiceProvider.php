<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; 
use App\Models\DoctorDisplaySchedule;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Setiap kali view 'welcome' diakses, jalankan logika ini secara otomatis
        View::composer('welcome', function ($view) {
            $dayMapping = [
                'monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu',
                'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu',
            ];

            $rawSchedules = DoctorDisplaySchedule::where('is_active', true)
                ->orderBy('doctor_name')
                ->get();

            $schedules = $rawSchedules->groupBy('doctor_name')->map(function ($docs) use ($dayMapping) {
                return $docs->groupBy('specialty')->map(function ($times) use ($dayMapping) {
                    return $times->groupBy(function ($item) {
                        return date('H:i', strtotime($item->start_time)) . ' - ' . date('H:i', strtotime($item->end_time));
                    })->map(function ($days) use ($dayMapping) {
                        return $days->map(fn($d) => $dayMapping[strtolower($d->day)] ?? $d->day)->join(', ');
                    });
                });
            });

            // Kirim variabel $schedules ke view
            $view->with('schedules', $schedules);
        });
    }
}
