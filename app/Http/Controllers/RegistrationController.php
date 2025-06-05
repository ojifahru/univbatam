<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\registration;

class RegistrationController extends Controller
{
    public function information()
    {
        $information = registration::where('id', 1)->first();
        $pageTitle = [
            'id' => 'Registrasi',
            'en' => 'Registration'
        ];
        $currentLocale = app()->getLocale();
        $pageTitle = $pageTitle[$currentLocale] ?? $pageTitle['en']; // Fallback to English if translation not found

        // Return the view with registrations data
        return view('pages.registrations', compact('pageTitle', 'information'));
    }

    public function procedure()
    {
        $procedure = registration::where('id', 2)->first();
        $pageTitle = [
            'id' => 'Prosedur Registrasi',
            'en' => 'Registration Procedure'
        ];
        $currentLocale = app()->getLocale();
        $pageTitle = $pageTitle[$currentLocale] ?? $pageTitle['en']; // Fallback to English if translation not found

        // Return the view with registrations data
        return view('pages.procedures', compact('pageTitle', 'procedure'));
    }
}
