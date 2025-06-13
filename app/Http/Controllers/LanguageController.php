<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request, $locale)
    {
        // Validate if the locale is supported
        if (!in_array($locale, ['en', 'km'])) {
            $locale = 'en'; // Default to English if not supported
        }
        
        // Store the locale in the session
        Session::put('locale', $locale);
        
        // Redirect back to the previous page
        return redirect()->back();
    }
}
