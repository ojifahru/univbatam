<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Slider;
use App\Models\Category;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function homePage()
    {
        // Ambil 6 berita terbaru dengan relasi kategori
        $news = News::with('category')->latest()->take(6)->get();

        // Ambil kategori berita yang unik
        $newsCategories = Category::whereIn('id', News::select('category_id')->distinct()->get()->pluck('category_id'))
            ->get();

        $sliders = Slider::latest()->take(3)->get();

        return view('pages.home', compact('news', 'sliders', 'newsCategories'));
    }

    public function privacy()
    {
        return view('pages.privacy-policy');
    }
}
