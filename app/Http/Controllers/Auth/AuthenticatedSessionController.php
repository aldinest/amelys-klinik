<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $maintenancePath = storage_path('app/maintenance.json');
        $maintenanceData = file_exists($maintenancePath)
            ? json_decode(file_get_contents($maintenancePath), true)
            : ['active' => false, 'message' => null];

        $maintenanceActive = $maintenanceData['active'] ?? false;
        $maintenanceMessage = $maintenanceData['message'] ?? null;

        return view('auth.login', compact('maintenanceActive', 'maintenanceMessage'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $maintenancePath = storage_path('app/maintenance.json');
        $maintenanceData = file_exists($maintenancePath)
            ? json_decode(file_get_contents($maintenancePath), true)
            : ['active' => false, 'message' => null];

        $user = Auth::user();
        $target = $maintenanceData['target'] ?? 'pengurus_pasien';
        $blockedForRole = false;

        if ($user && ! $user->isAdmin()) {
            if ($target === 'pasien' && $user->isPasien()) {
                $blockedForRole = true;
            } elseif ($target === 'pengurus_pasien' && ($user->isPengurus() || $user->isPasien())) {
                $blockedForRole = true;
            }
        }

        if (($maintenanceData['active'] ?? false) && $blockedForRole) {
            return redirect()->route('maintenance.page')
                ->with('warning', 'Akun Anda saat ini tidak dapat mengakses sistem karena maintenance untuk role Anda.');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
