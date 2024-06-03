<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Localization
{
    private const HEADER_X_LOCALIZATION = 'X-localization';
    private const VALID_LANGUAGES = ['pt', 'en'];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $local = $this->getHeaderXLocalizationOrDefault($request);

        app()->setLocale($local);

        return $next($request);
    }

    private function getHeaderXLocalizationOrDefault(Request $request): string
    {
        $local = 'en';
        $xLocalization = $request->header(self::HEADER_X_LOCALIZATION);

        if (
            $request->hasHeader(self::HEADER_X_LOCALIZATION)
            && in_array($xLocalization, self::VALID_LANGUAGES)
        ) {
            $local = $xLocalization;
        }

        return $local;
    }
}
