<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\TokenModel;
use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;
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
        $uid= $request->get('user_id');
        $url=$_SERVER["REQUEST_URI"];
        echo $url;
        if(strpos($url,'?')){
            $url=substr($url,0,strpos($url,'?'));
        }

        $key = 'c:view_count:'.$uid;
        Redis::hincrby($key,$url,1);
        return $next($request);
    }
}
