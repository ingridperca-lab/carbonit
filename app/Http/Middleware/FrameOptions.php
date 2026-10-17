<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Paginile pot fi incluse în iframe doar de pe același domeniu; /embed/* doar de pe site-urile din config/embed.php. */
class FrameOptions
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->is('embed/*')) {
            $response->headers->set('Content-Security-Policy', 'frame-ancestors '.implode(' ', config('embed.origins')));
        } else {
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        }

        return $response;
    }
}
