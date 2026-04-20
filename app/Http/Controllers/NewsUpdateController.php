<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsUpdateController extends Controller
{
    public function index()
    {
        $news = \App\Models\News::latest()->take(3)->get();
        
        // Cukup kirim news saja
        return view('welcome', compact('news')); 
    }
    
}
