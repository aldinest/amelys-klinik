<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Patient;
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
        return view('pengurus.patients.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        return view('pengurus.patients.show', compact('patient'));
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
