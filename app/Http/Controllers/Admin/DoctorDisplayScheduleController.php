<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorDisplaySchedule;
use Illuminate\Http\Request;

class DoctorDisplayScheduleController extends Controller
{
    public function index()
    {
        $schedules = DoctorDisplaySchedule::orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('admin.doctor-display-schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('admin.doctor-display-schedules.create');
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
        'doctor_name' => 'required|string|max:255',
        'specialty'   => 'nullable|string|max:255',
        'day'         => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        'start_time'  => 'required|date_format:H:i',
        'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        // checkbox handling (INI KUNCI)
        $validated['is_active'] = $request->has('is_active');

        DoctorDisplaySchedule::create($validated);

        return redirect()
            ->route('admin.doctor-display-schedules.index')
            ->with('success', 'Jadwal dokter berhasil ditambahkan');
    }

    public function edit(DoctorDisplaySchedule $doctorDisplaySchedule)
    {
        return view(
            'admin.doctor-display-schedules.edit',
            ['schedule' => $doctorDisplaySchedule]
        );
    }

    public function update(Request $request, DoctorDisplaySchedule $doctorDisplaySchedule)
    {
        $validated = $request->validate([
            'doctor_name' => 'required|string|max:255',
            'specialty'   => 'nullable|string|max:255',
            'day'         => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        // INI PENTING BANGET
        $validated['is_active'] = $request->has('is_active');

        $doctorDisplaySchedule->update($validated);

        return redirect()
            ->route('admin.doctor-display-schedules.index')
            ->with('success', 'Jadwal dokter berhasil diperbarui');
    }

    public function destroy(DoctorDisplaySchedule $doctorDisplaySchedule)
    {
        $doctorDisplaySchedule->delete();

        return redirect()
            ->route('admin.doctor-display-schedules.index')
            ->with('success', 'Doctor schedule deleted');
    }

    public function toggle(DoctorDisplaySchedule $doctorDisplaySchedule)
    {
        $doctorDisplaySchedule->update([
            'is_active' => ! $doctorDisplaySchedule->is_active
        ]);

        return back();
    }
}
