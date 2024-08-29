<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionLang = Session::get('locale');
        if ($sessionLang) {
            App::setlocale($sessionLang);
        } else {
            Session::put('locale', 'en');
            App::setlocale(Session::get('locale'));
        }

        if ($request->wantsJson()) {
            $lang = $request->header('Accept-language');
            App::setLocale($lang);
        }
        return $next($request);
    }
}
