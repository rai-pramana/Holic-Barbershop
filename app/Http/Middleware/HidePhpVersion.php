<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HidePhpVersion
{
    /**
     * Sembunyikan identitas PHP (x-powered-by) dari setiap response.
     * expose_php tidak bisa dimatikan via CLI tanpa path php.ini yang tepat,
     * jadi dibersihkan di level aplikasi.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $response->headers->remove('X-Powered-By');
        header_remove('X-Powered-By');

        return $response;
    }
}
