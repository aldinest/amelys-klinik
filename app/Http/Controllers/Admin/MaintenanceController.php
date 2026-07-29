<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
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

    public function index()
    {
        $maintenance = $this->maintenanceData();

        return view('admin.maintenance', [
            'isDown' => $maintenance['active'],
            'message' => $maintenance['message'],
        ]);
    }

    public function enable(Request $request)
    {
        $message = $request->input('message', 'Aplikasi saat ini sedang dalam perawatan. Silakan cek kembali beberapa saat lagi.');

        file_put_contents($this->maintenanceFilePath(), json_encode([
            'active' => true,
            'message' => $message,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Maintenance mode telah diaktifkan.');
    }

    public function disable(Request $request)
    {
        if (file_exists($this->maintenanceFilePath())) {
            unlink($this->maintenanceFilePath());
        }

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Maintenance mode telah dinonaktifkan.');
    }
}
