<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 

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
        // Jika config() mengembalikan null, kita paksa ambil langsung dari env() buat jaga-jaga cache
        $appId = config('services.onesignal.app_id') ?? env('ONESIGNAL_APP_ID');
        $restKey = config('services.onesignal.rest_api_key') ?? env('ONESIGNAL_REST_API_KEY');

        $url = 'https://onesignal.com/api/v1/notifications';

        $data = [
            'app_id' => $appId,
            'included_segments' => ['All'], 
            'headings' => [
                'en' => "Info Baru: " . trim($news->title)
            ],
            'contents' => [
                'en' => "Ada kabar terbaru dari Amelys nih, cek yuk!"
            ],
            'chrome_web_icon' => asset('dist/img/logoamelys.png'), 
            'url' => route('welcome'), 
        ];

        // Eksekusi tembak API
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . trim($restKey),
            'Content-Type'  => 'application/json',
        ])->post($url, $data);

        // ==========================================
        // SEKSI DEBUGGING (Biar Kelihatan Error-nya)
        // ==========================================
        if ($response->failed()) {
            // Kalau gagal, Laravel bakal stop dan nampilin pesan error asli dari OneSignal
            dd([
                'Status Error' => 'Gagal Nembak API OneSignal!',
                'Pesan dari OneSignal' => $response->json(),
                'Kunci APP_ID lo' => $appId,
                'Kunci REST_KEY lo' => $restKey ? 'Sudah Terisi (Aman)' : 'KOSONG / TIDAK TERBACA!'
            ]);
        }
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