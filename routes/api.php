<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ScheduleController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/save-token', function (Request $request) {
    // Cari user yang lagi aktif ngetes (misal Aldo atau admin)
    // Untuk ngetes, kita update user pertama saja
    $user = User::first(); 
    
    if ($user) {
        $user->update(['fcm_token' => $request->token]);
        return response()->json(['message' => 'Token berhasil disimpan!']);
    }

    return response()->json(['message' => 'User tidak ditemukan'], 404);
});
