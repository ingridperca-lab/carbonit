<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/** Panoul de admin răspunde doar pe hosturile din config('admin.hosts'). */
class AdminDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        URL::defaults(['adminHost' => $request->route('adminHost')]);

        return $next($request);
    }
}
