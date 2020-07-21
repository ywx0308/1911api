<?php

namespace App\Http\Middleware;

use Closure;

class ViewToken
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
        $token = request()->get("token");
        if(empty($token)){
            $response=[
                "error"=>"40007",
                "msg"=>"请输入token"
            ];
            return $response;
        }
        return $next($request);
    }
}
