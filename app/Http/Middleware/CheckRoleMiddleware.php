<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Periksa apakah user memiliki session dan role yang sesuai
        if (!session()->has('role') || session('role') !== $role) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
