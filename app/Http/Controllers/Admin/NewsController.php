<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // ... method index, create, store tetap sama ...

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

        News::create($request->all());

        return redirect()->route('admin.news.index')->with('success', 'Info terbaru berhasil diterbitkan!');
    }

    /**
     * Menampilkan form edit info
     */
    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Mengupdate data info ke database
     */
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
        $news->update([
            'title'       => $request->title,
            'date'        => $request->date,
            'description' => $request->description,
            'author_name' => $request->author_name,
            'author_role' => $request->author_role,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Info berhasil diperbarui!');
    }

    public function destroy($id)
    {
        News::findOrFail($id)->delete();
        return back()->with('success', 'Info berhasil dihapus.');
    }
}