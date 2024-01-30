<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switchLanguage($locale)
    {
        if (array_key_exists($locale, config('app.supported_locales'))) {
            session()->put('locale', $locale);
        }

        return redirect()->back(); // Redirect back to the previous page
    }
}
