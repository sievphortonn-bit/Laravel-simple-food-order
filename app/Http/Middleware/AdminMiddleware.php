<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle($request, Closure $next)
    {
        if (!auth()->check() || (int) auth()->user()->is_admin !== 1) {
            return redirect()->route('admin.login')->with('error', 'Access denied.');
        }

        return $next($request);
    }

}
