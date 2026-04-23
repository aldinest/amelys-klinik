<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Exports\PatientsExport;
use App\Imports\PatientsImport;
use Maatwebsite\Excel\Facades\Excel;
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

    public function export()
    {
        return Excel::download(new PatientsExport, 'patients.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $import = new PatientsImport;
        Excel::import($import, $request->file('file'));

        if (count($import->duplicates) > 0) {
            return back()->with([
                'success' => 'Data berhasil diimport',
                'warning' => 'Data duplikat diskip: ' . implode(', ', $import->duplicates)
            ]);
        }

        return back()->with('success', 'Semua data berhasil diimport tanpa duplikat');
    }

    // Admin/PatientController.php
    public function createAccount(Request $request, Patient $patient)
    {
        // 1. Validasi input email
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ], [
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        // 2. Kalau sudah punya akun, stop
        if ($patient->user_id) {
            return back()->with('error', 'Pasien ini sudah memiliki akun aplikasi.');
        }

        // 3. Logika Password: nama depan + tgl lahir (format dmy)
        // Ambil nama depan (huruf kecil)
        $firstName = strtolower(explode(' ', trim($patient->name))[0]);
        
        // Ambil tanggal lahir (format 17081945), jika tidak ada pakai default 123456
        $formattedDob = $patient->date_of_birth 
            ? \Carbon\Carbon::parse($patient->date_of_birth)->format('dmY') 
            : '123456';

        $passwordDefault = $firstName . $formattedDob;

        try {
            // 4. Buat User baru
            $user = User::create([
                'name'     => $patient->name,
                'email'    => $request->email,
                'password' => bcrypt($passwordDefault),
                'role'     => 'pasien',
            ]);

            // 5. Hubungkan ke data pasien
            $patient->update([
                'user_id' => $user->id
            ]);

            return back()->with('success', "Akun berhasil diaktivasi! Password: $passwordDefault");

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
