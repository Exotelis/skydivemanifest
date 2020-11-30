<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Class Localization
 * @package App\Http\Middleware
 */
class Localization
{
    /**
     * Set default localization options.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle ($request, Closure $next)
    {
        $locale = config('app.locale');

        if($request->wantsJson()) {
            if ($request->hasHeader('Content-Language')) {
                $locale = $request->header('Content-Language');
            }
        } else {
            if (! \is_null($request->route('language'))) {
                $locale = $request->route('language');
            }
        }

        App::setLocale($locale);

        // Is usually defined by App:setLocale, for safety reasons let's set it anyway
        Carbon::setLocale($locale);
        Carbon::setFallbackLocale(config('app.locale'));

        return $next($request);
    }
}
