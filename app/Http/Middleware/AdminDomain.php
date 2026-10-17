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
        // Parametrul de domeniu nu trebuie să ajungă ca argument în controllere.
        $request->route()->forgetParameter('adminHost');

        return $next($request);
    }
}
