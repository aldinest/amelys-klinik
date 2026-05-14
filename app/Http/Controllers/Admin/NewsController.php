<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Tetap pakai library andalan lo buat nembak API

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->get();
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'required|date',
            'description' => 'required',
            'author_name' => 'required|string',
            'author_role' => 'required|string',
        ]);

        // 1. Simpan data berita ke database internal
        $news = News::create($request->all());

        // 2. LOGIKA ONE SIGNAL (Broadcaster ke HP Pasien/User)
        // Gak perlu tarik token dari DB, langsung tembak ke semua yang Subscribe lewat OneSignal
        $this->sendOneSignalNotification($news);

        return redirect()->route('admin.news.index')->with('success', 'Info terbaru berhasil diterbitkan dan notifikasi OneSignal terkirim!');
    }

    /**
     * Remake Fungsi Helper untuk nembak API OneSignal
     * Lebih simple dari FCM bre!
     */
    private function sendOneSignalNotification($news)
    {
        $appId = config('services.onesignal.app_id');
        $restKey = config('services.onesignal.rest_api_key');

        $url = 'https://onesignal.com/api/v1/notifications';

        // Persiapan data buat dikirim ke API OneSignal
        $data = [
            'app_id' => $appId,
            'included_segments' => ['All'], // Mengirim ke SEMUA orang yang sudah klik "Subscribe"
            'headings' => [
                'en' => "Info Baru: " . $news->title
            ],
            'contents' => [
                'en' => "Ada kabar terbaru dari Amelys nih, cek yuk!"
            ],
            'chrome_web_icon' => asset('dist/img/logoamelys.png'), // Logo klinik kamu
            'url' => route('welcome'), // Tujuan pas diklik
        ];

        // Eksekusi "tembak" API OneSignal
        Http::withHeaders([
            'Authorization' => 'Basic ' . $restKey,
            'Content-Type'  => 'application/json',
        ])->post($url, $data);
    }

    // Fungsi edit, update, destroy tetap sama...
    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'required|date',
            'description' => 'required',
            'author_name' => 'required|string',
            'author_role' => 'required|string',
        ]);

        $news = News::findOrFail($id);
        $news->update($request->all());

        return redirect()->route('admin.news.index')->with('success', 'Info berhasil diperbarui!');
    }

    public function destroy($id)
    {
        News::findOrFail($id)->delete();
        return back()->with('success', 'Info berhasil dihapus.');
    }
}