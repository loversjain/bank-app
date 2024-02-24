<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Attempt to verify the JWT token
            $user = \Auth::guard('api')->authenticate();
        } catch (JWTException $e) {
            // Token verification failed
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Token verification successful, proceed to the intended route
        return $next($request);
    }
}
