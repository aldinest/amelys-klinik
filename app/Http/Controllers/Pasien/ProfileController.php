<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        return view('pasien.profile.index');
    }

    // UPDATE NAMA
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // UPDATE PASSWORD
    public function updatePassword(Request $request)
    {
        $request->validate([
            // current_password dihapus dari validasi
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.'
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
