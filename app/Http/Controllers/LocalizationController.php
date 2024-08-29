<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($lang)
    {
        App::setLocale($lang);
        Session::put('locale', $lang);
        return back();
    }
}
