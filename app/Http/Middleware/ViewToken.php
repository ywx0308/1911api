<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\TokenModel;

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
        echo date("Y-m-d H:i:s");
        $token = request()->get("token");
        if(empty($token)){
            $response=[
                "error"=>"40007",
                "msg"=>"未授权"
            ];
            return response()->json($response);
        }
        $data=TokenModel::where(["token"=>$token])->first();
        if(!$data){
            $response=[
                "error"=>"40008",
                "msg"=>"授权失败"
            ];
            return response()->json($response);
        }
        $request->attributes->add(['user_id'=>$data->user_id]);
        return $next($request);
    }
}
