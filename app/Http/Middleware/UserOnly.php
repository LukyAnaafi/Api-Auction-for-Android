<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->level === 'user'){
            return $next($request);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki izin untuk melalukan hal ini',
                'data' => []
            ],401);
        }
        
    }
}
