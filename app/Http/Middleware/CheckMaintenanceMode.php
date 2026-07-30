<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    private function maintenanceFilePath(): string
    {
        return storage_path('app/maintenance.json');
    }

    private function maintenanceData(): array
    {
        $path = $this->maintenanceFilePath();

        if (! file_exists($path)) {
            return ['active' => false, 'message' => null, 'target' => 'pengurus_pasien'];
        }

        return json_decode(file_get_contents($path), true) ?: ['active' => false, 'message' => null, 'target' => 'pengurus_pasien'];
    }

    private function shouldBlockUser(array $maintenance, ?
    \App\Models\User $user = null): bool
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
        $maintenance = $this->maintenanceData();

        if ($request->routeIs('maintenance.page')) {
            return $next($request);
        }

        if (! $this->shouldBlockUser($maintenance, Auth::user())) {
            return $next($request);
        }

        return redirect()->route('maintenance.page');
    }
}
