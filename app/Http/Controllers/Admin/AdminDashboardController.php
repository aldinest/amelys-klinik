<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Models\DoctorDisplaySchedule;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Mengambil data sesuai urutan menu di sidebar
        $countDokter = Doctor::count();
        $countPasien = Patient::count();
        $countAkunUser = User::count();
        $countJadwal = DoctorDisplaySchedule::count();

        return view('admin.dashboard', compact(
            'countDokter', 
            'countPasien', 
            'countAkunUser', 
            'countJadwal'
        ));
    }
}
