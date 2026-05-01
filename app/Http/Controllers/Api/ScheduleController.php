<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        // Ambil semua data jadwal dari database
        $schedules = DoctorSchedule::all();

        // Kirim data sebagai JSON agar bisa dibaca "mesin" atau aplikasi lain
        return response()->json([
            'status'  => 'success',
            'message' => 'Data Jadwal Dokter Amelys Klinik',
            'data'    => $schedules
        ], 200);
    }
}
