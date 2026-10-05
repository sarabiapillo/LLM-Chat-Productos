<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
use App\Models\AuditLog;

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => $request->method() . ' ' . $request->path(),
                'ip_address' => $request->ip(),
                'details' => json_encode($request->except(['password', 'password_confirmation'])),
            ]);
        }

        return $next($request);
    }
}
