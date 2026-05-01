<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User; // Penting: Untuk ambil token fcm pasien
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Penting: Library buat "nembak" API luar

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

        // 1. Simpan data berita ke database internal kita
        $news = News::create($request->all());

        // 2. LOGIKA API BROADCASTER (Kirim ke HP Pasien)
        // Kita ambil semua token FCM dari database yang sudah "allow" notifikasi
        $tokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

        if (!empty($tokens)) {
            $this->sendPushNotification($tokens, $news);
        }

        return redirect()->route('admin.news.index')->with('success', 'Info terbaru berhasil diterbitkan dan notifikasi terkirim!');
    }

    /**
     * Fungsi Helper untuk nembak API Firebase (FCM)
     * Ini inti dari pembelajaran API kita bre!
     */
    private function sendPushNotification($tokens, $news)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = 'AIzaSyB56s9ttNhZWd7dYuVJoCEe3t6FCsrd9NY'; // Nanti ambil dari console firebase

        $data = [
            "registration_ids" => $tokens, // Array token-token HP pasien
            "notification" => [
                "title" => "Info Baru: " . $news->title,
                "body"  => "Ada kabar terbaru dari Amelys Klinik nih, cek yuk!",
                "icon"  => asset('dist/img/logoamelys.png'), // Logo klinik kamu
                "click_action" => route('welcome') // Ke mana pasien diarahkan pas ngeklik notif
            ]
        ];

        // Di sini Laravel kamu bertindak sebagai Client yang nembak API Google
        Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type'  => 'application/json',
        ])->post($url, $data);
    }

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