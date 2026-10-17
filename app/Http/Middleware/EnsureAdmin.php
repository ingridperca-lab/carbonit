<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $t = (int) $request->session()->get('admin_at', 0);
        if (! $request->session()->get('admin') || $t < time() - 8 * 3600) {
            if ($request->expectsJson()) {
                return response()->json(['ok' => false, 'error' => 'auth'], 401);
            }

            return redirect()->route('admin.login');
        }

        return $next($request)->header('Cache-Control', 'no-store');
    }
}
