<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class adminnotlogedin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('admin')) {
            return redirect('admin/dashboard');
        }
        return $next($request);
    }
}
