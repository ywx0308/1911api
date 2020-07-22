<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class ViewSum
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

        $url = $_SERVER["REQUEST_URI"];
        $res = strpos($url,'?');
        if($res){
            $url=substr($url,0,$res);
        }
        $key="z:view_sum";
        Redis::zincrby($key,1,$url);
        return $next($request);
    }
}
