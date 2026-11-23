<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * DetectTimezone — reads the client timezone from cookie or header
 * and makes it available as a request attribute for Livewire components.
 */
class DetectTimezone
{
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: 1) X-Client-Timezone header (sent by JS on Livewire requests)
        //           2) client_timezone cookie (set on first page load)
        //           3) fallback to UTC
        $timezone = $request->header('X-Client-Timezone')
            ?? $request->cookie('client_timezone')
            ?? 'UTC';

        // Sanitise — ensure it's a valid PHP timezone
        if (! in_array($timezone, timezone_identifiers_list(), true)) {
            $timezone = 'UTC';
        }

        $request->merge(['__detected_timezone' => $timezone]);

        return $next($request);
    }
}
