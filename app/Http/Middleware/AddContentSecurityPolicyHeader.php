<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AddContentSecurityPolicyHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $cspPolicy = "default-src 'self'; script-src 'self'; style-src 'self';";

        $response->header('Content-Security-Policy', $cspPolicy);

        return $response;
    }
}
