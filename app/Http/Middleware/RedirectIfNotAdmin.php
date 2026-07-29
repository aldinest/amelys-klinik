<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $maintenancePath = storage_path('app/maintenance.json');
        $maintenanceData = file_exists($maintenancePath)
            ? json_decode(file_get_contents($maintenancePath), true)
            : ['active' => false, 'message' => null];

        if (! ($maintenanceData['active'] ?? false)) {
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
