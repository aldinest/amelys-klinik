<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdmin
{
    private function maintenanceData(): array
    {
        $maintenancePath = storage_path('app/maintenance.json');

        if (! file_exists($maintenancePath)) {
            return ['active' => false, 'message' => null, 'target' => 'pengurus_pasien'];
        }

        return json_decode(file_get_contents($maintenancePath), true) ?: ['active' => false, 'message' => null, 'target' => 'pengurus_pasien'];
    }

    private function shouldBlockUser(array $maintenance, ?\App\Models\User $user = null): bool
    {
        if (! ($maintenance['active'] ?? false)) {
            return false;
        }

        if ($user && $user->isAdmin()) {
            return false;
        }

        $target = $maintenance['target'] ?? 'pengurus_pasien';

        if ($target === 'pasien') {
            return $user?->isPasien() ?? false;
        }

        if ($target === 'pengurus_pasien') {
            return $user?->isPengurus() || $user?->isPasien() ?? false;
        }

        return false;
    }

    public function handle(Request $request, Closure $next)
    {
        $maintenanceData = $this->maintenanceData();

        if ($request->routeIs('maintenance.page')) {
            return $next($request);
        }

        if (! $this->shouldBlockUser($maintenanceData, Auth::user())) {
            return $next($request);
        }

        return redirect()->route('maintenance.page');
    }
}
