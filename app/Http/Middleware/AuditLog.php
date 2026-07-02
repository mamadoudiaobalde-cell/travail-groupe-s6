<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuditLog
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (auth()->check()) {
            Log::channel('audit')->info('User Action', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'method' => $request->method(),
                'path' => $request->path(),
                'ip' => $request->ip(),
                'timestamp' => now(),
            ]);
        }

        return $response;
    }
}