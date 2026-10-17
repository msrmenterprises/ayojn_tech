<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=()');
        $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');

        // Basic Content Security Policy example
        //$response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self'; style-src 'self'");
     
        $response->headers->set(
    'Content-Security-Policy',
    "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' cdnjs.cloudflare.com ajax.googleapis.com code.jquery.com cdn.datatables.net gitcdn.github.io cdn.jsdelivr.net unpkg.com www.googletagmanager.com app.getterms.io www.google-analytics.com cdn.ckeditor.com www.google.com www.gstatic.com; style-src 'self' 'unsafe-inline' cdn.datatables.net cdnjs.cloudflare.com fonts.googleapis.com gitcdn.github.io use.fontawesome.com; font-src 'self' cdnjs.cloudflare.com use.fontawesome.com fonts.gstatic.com; connect-src 'self' www.google-analytics.com http://127.0.0.1:8001 https://dev-chat.ayojn.com https://chat.ayojn.com; img-src 'self' data: loading.io www.googletagmanager.com; frame-src 'self' www.google.com www.gstatic.com;"
);
        return $response;
    }
}
