<?php

namespace App\Http\Middleware;

use App\Services\LicenseService;
use Closure;
use Illuminate\Http\Request;

class EnsureFeatureEnabled
{
    public function handle(Request $request, Closure $next, string $feature): mixed
    {
        abort_if(
            !LicenseService::has($feature),
            403,
            'This feature requires a Pro license. Go to Settings → License to activate.'
        );

        return $next($request);
    }
}
