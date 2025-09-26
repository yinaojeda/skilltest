<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = optional($request->user())->id;

        Log::info('api-request', [
            'user_id'   => $userId,
            'method'    => $request->method(),
            'endpoint'  => $request->path(),            // just the URI, no domain
            'timestamp' => now()->toDateTimeString(),
        ]);

        return $next($request);
    }
}
