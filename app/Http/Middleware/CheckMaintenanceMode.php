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
            return ['active' => false, 'message' => null];
        }

        return json_decode(file_get_contents($path), true) ?: ['active' => false, 'message' => null];
    }

    public function handle(Request $request, Closure $next)
    {
        $maintenance = $this->maintenanceData();

        if (! ($maintenance['active'] ?? false)) {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        if ($request->routeIs('maintenance.page')) {
            return $next($request);
        }

        return redirect()->route('maintenance.page');
    }
}
