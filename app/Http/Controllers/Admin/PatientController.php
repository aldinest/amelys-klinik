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
    // 1. Validasi input pasien & email untuk user sekaligus
    $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => 'required|email|unique:users,email', // Pastikan email unik di tabel users
        'phone'         => 'nullable',
        'gender'        => 'required',
        'date_of_birth' => 'required|date',
    ], [
        'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
    ]);

    \DB::beginTransaction();

    try {
        // 2. Logika Password Otomatis (Copas dari logika createAccount lo)
        $firstName = strtolower(explode(' ', trim($request->name))[0]);
        $formattedDob = \Carbon\Carbon::parse($request->date_of_birth)->format('dmY');
        $passwordDefault = $firstName . $formattedDob;

        // 3. Buat User Account
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($passwordDefault),
            'role'     => 'pasien',
        ]);

        // 4. Buat Data Pasien dan hubungkan ke user_id
        Patient::create([
            'user_id'       => $user->id,
            'name'          => $request->name,
            'address'       => $request->address,
            'phone'         => $request->phone,
            'gender'        => $request->gender,
            'date_of_birth' => $request->date_of_birth,
        ]);

        \DB::commit();

        return redirect()->route('admin.patients.index')
            ->with('success', "Pasien & Akun berhasil dibuat! Password: $passwordDefault");

    } catch (\Exception $e) {
        \DB::rollback();
        return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
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
        try {
            // 1. Cek apakah pasien punya relasi user_id
            if ($patient->user_id) {
                // Ambil data user-nya
                $user = \App\Models\User::find($patient->user_id);
                
                if ($user) {
                    $user->delete(); // Hapus akun user-nya
                }
            }

            // 2. Hapus data pasiennya
            $patient->delete();

            return redirect()->route('admin.patients.index')
                ->with('success', 'Data pasien dan akun login berhasil dihapus.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
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



}
