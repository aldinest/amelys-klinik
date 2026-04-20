<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PengurusDashboardController extends Controller
{
    public function index()
    {
        $data = [
        'totalPasien' => \App\Models\Patient::count(),
        'pasienHariIni' => \App\Models\Reservation::whereDate('created_at', today())->count(),
        'totalDokter' => \App\Models\DoctorDisplaySchedule::distinct('doctor_name')->count(),
        'reservasiTerbaru' => \App\Models\Reservation::with('patient')->latest()->take(5)->get(),
    ];
    return view('pengurus.dashboard', $data);
    }
}
