<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expiration = Auth::payload()->get('exp'); // Lấy thời hạn token

        if (Carbon::now()->gte(Carbon::createFromTimestamp($expiration))) {
            // Token đã hết hạn
            return response()->json(['error' => 'Token has expired'], 401);
        }

        return $next($request);
    }
}
