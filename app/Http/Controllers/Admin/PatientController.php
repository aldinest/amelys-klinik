<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $patients = Patient::latest()
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%');
            })
            ->paginate(10);

        return view('admin.patients.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        return view('admin.patients.show', compact('patient'));
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        Patient::create($request->only([
            'name',
            'address',
            'phone',
            'gender',
            'date_of_birth',
            // 'medical_records',
        ]));

        return redirect()->route('admin.patients.index')
            ->with('success', 'Pasien ditambahkan');
    }

    public function edit(Patient $patient)
    {
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $patient->update($request->only([
            'name',
            'address',
            'phone',
            'gender',
            'date_of_birth',
            // 'medical_records',
        ]));

        return redirect()->route('admin.patients.index')
            ->with('success', 'Data pasien diperbarui');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return back()->with('success', 'Pasien dihapus');
    }

    // Admin/PatientController.php
    public function createAccount(Patient $patient)
    {
        // kalau sudah punya akun, stop
        if ($patient->user_id) {
            return back()->with('error', 'Akun sudah ada');
        }

        $user = User::create([
            'name'     => $patient->name,
            'email'    => request('email'),
            'password' => bcrypt('password123'), // default
            'role'     => 'pasien',
        ]);

        $patient->update([
            'user_id' => $user->id
        ]);

        return back()->with('success', 'Akun pasien berhasil dibuat');
    }

}
