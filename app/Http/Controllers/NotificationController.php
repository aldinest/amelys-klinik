<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $layout = match (auth()->user()->role) {
            'admin' => 'layouts.applte',
            'pengurus' => 'layouts.app_pengurus',
            'pasien' => 'layouts.app_pasien',
            default => 'layouts.app_pengurus',
        };

        return view('notifications.index', compact('layout'));
    }

    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        $routeName = auth()->user()->role . '.notifications.index';
        if (!\Illuminate\Support\Facades\Route::has($routeName)) {
            $routeName = 'notifications.index';
        }

        return redirect()->route($routeName)->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }
}
