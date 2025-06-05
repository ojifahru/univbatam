<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Identity;

class IdentityController extends Controller
{
    public function index()
    {
        $pageTitle = [
            'id' => 'Identitas Universitas',
            'en' => 'University Identities'
        ];
        $currentLocale = app()->getLocale();
        $pageTitle = $pageTitle[$currentLocale] ?? $pageTitle['en']; // Fallback to English if translation not found

        // Return the view with identities data
        return view('pages.identities', compact('pageTitle'));
    }
}
