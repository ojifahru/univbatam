<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        App::setLocale(Session::get('locale', config('app.locale')));

        $response = $next($request);

        // Check if we are on a facility detail page
        if ($request->route() && $request->route()->getName() === 'facility.detail') {
            $slug = $request->route('slug');

            // Store the facility slug in session for language switching
            session(['current_facility_slug' => $slug]);
        }

        return $response;
    }
}
