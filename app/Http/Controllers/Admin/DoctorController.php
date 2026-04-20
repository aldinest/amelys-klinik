<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctors = Doctor::latest()
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            })
            ->paginate(10);

        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string',
            'address'        => 'nullable|string',
            'phone_number'   => 'nullable|string',
            'specialist'     => 'nullable|string',
            'gender'         => 'required|in:L,P',
            'date_of_birth'  => 'nullable|date',
            'status'         => 'required|in:aktif,nonaktif',
        ]);

        Doctor::create($data);

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Dokter berhasil ditambahkan');
    }

    public function edit(Doctor $doctor)
    {
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'name'           => 'required|string',
            'address'        => 'nullable|string',
            'phone_number'   => 'nullable|string',
            'specialist'     => 'nullable|string',
            'gender'         => 'required|in:L,P',
            'date_of_birth'  => 'nullable|date',
            'status'         => 'required|in:aktif,nonaktif',
        ]);

        $doctor->update($data);

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Data dokter diperbarui');
    }

    public function show(Doctor $doctor)
    {
        return view('admin.doctors.show', compact('doctor'));
    }

    public function destroy(Doctor $doctor)
    {
        //dd('MASUK DESTROY', $doctor);
        if ($doctor->status === 'aktif') {
            return back()->with('error', 'Nonaktifkan dokter dulu sebelum menghapus');
        }

        $doctor->delete();

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Dokter berhasil dihapus');
    }

    // Admin/DoctorController.php
    public function createAccount(Doctor $doctor)
    {
        // kalau sudah punya akun, stop
        if ($doctor->user_id) {
            return back()->with('error', 'Akun sudah ada');
        }

        $user = User::create([
            'name'     => $doctor->name,
            'email'    => request('email'),
            'password' => bcrypt('password123'), // default
            'role'     => 'pengurus',
        ]);

        $doctor->update([
            'user_id' => $user->id
        ]);

        return back()->with('success', 'Akun dokter berhasil dibuat');
    }

}
