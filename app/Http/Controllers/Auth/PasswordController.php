<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // Hanya validasi password baru & konfirmasinya
        $validated = $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Update password di database
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Kirim status 'password-updated' agar alert muncul di Blade
        return back()->with('status', 'password-updated');
    }
}
