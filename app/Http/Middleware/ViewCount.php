<?php

namespace App\Http\Middleware;

use Closure;

class ViewCount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        echo date("Y-m-d H:i:s");
        return $next($request);
    }
}
