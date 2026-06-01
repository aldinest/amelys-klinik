<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua dokter untuk dropdown
        $doctors = Doctor::orderBy('name', 'asc')->get();

        // 2. Inisialisasi Query
        $query = DoctorSchedule::query()->with('doctor');

        // 3. Logika Filter Tanggal (Meniru ReservasiController)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('schedule_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('schedule_date', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('schedule_date', $request->end_date);
        }

        // 4. Filter Dokter
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // 5. Eksekusi Query
        $schedules = $query->orderBy('schedule_date', 'desc')
                        ->paginate(10)
                        ->withQueryString(); // SANGAT PENTING agar filter tetap tersimpan saat pindah halaman

        return view('pengurus.doctor_schedules.index', compact('schedules', 'doctors'));
    }

    public function create()
    {
        $doctors = Doctor::where('status', 'aktif')->get();
        return view('pengurus.doctor_schedules.create', compact('doctors'));
    }

    public function store(Request $request)
    {

    $request->validate([
    'doctor_id'     => 'required|exists:doctors,id',
    'schedule_date' => 'required|date',
    'start_time'    => 'required',
    'end_time'      => 'required|after:start_time',
    'quota'         => 'required|integer|min:1',
    ], [
        'schedule_date.required' => 'Tanggal jadwal wajib diisi',
        'end_time.after'         => 'Jam selesai harus lebih besar dari jam mulai',
    ]);

    DoctorSchedule::create([
        'doctor_id'     => $request->doctor_id,
        'schedule_date' => $request->schedule_date,
        'start_time'    => $request->start_time,
        'end_time'      => $request->end_time,
        'quota'         => $request->quota,
    ]);

        return redirect()
            ->route('pengurus.doctor_schedules.index')
            ->with('success', 'Jadwal Berhasil Ditambahkan');
    }

    public function edit(DoctorSchedule $doctorSchedule)
    {
        $doctors = Doctor::where('status', 'aktif')->get();
        return view('pengurus.doctor_schedules.edit', compact('doctorSchedule', 'doctors'));
    }

    public function update(Request $request, DoctorSchedule $doctor_schedule)
    {

    $request->validate([
        'doctor_id'     => 'required|exists:doctors,id',
        'schedule_date' => 'required|date',
        'start_time'    => 'required',
        'end_time'      => 'required|after:start_time',
        'quota'         => 'required|integer|min:1',
    ], [
        'schedule_date.required' => 'Tanggal jadwal wajib diisi',
        'end_time.after'         => 'Jam selesai harus lebih besar dari jam mulai',
    ]);

    $doctor_schedule->update([
        'doctor_id'     => $request->doctor_id,
        'schedule_date' => $request->schedule_date,
        'start_time'    => $request->start_time,
        'end_time'      => $request->end_time,
        'quota'         => $request->quota,
    ]);

        return redirect()
            ->route('pengurus.doctor_schedules.index')
            ->with('success', 'Jadwal Berhasil Di Update');
    }

    public function destroy(DoctorSchedule $doctorSchedule)
    {
        $doctorSchedule->delete();

        return redirect()
            ->route('pengurus.doctor_schedules.index')
            ->with('success', 'Jadwal Berhasil Di Hapus');
    }
}
