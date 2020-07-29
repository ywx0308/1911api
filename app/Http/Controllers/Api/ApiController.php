<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use App\Model\TokenModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    //注册
    public function reg(Request $request){
        $user_name=$request->post("user_name");
        $user_email= $request->post("email");
        $user_pwd = $request->input("user_pwd");

        $user_pwd3=password_hash($user_pwd,PASSWORD_DEFAULT);
        $data=[
            "user_name" => $user_name,
            "email"     => $user_email,
            "user_pwd" =>  $user_pwd3
        ];

        $res = UserModel::create($data);
        if($res){
            $response=[
                "error"=>"0",
                "msg"=>"注册成功"
            ];
        }else{
            $response=[
                "error"=>"40005",
                "msg"=>"注册失败"
            ];
        }
        return $response;

    }
    //登录
    public function login(){
        $name = request()->get("user_name");
        $pwd = request()->get("user_pwd");
        $user=UserModel::where(["user_name"=>$name])->first();
        if($user){
            $pwd = password_verify($pwd,$user->user_pwd);
            //生成token
            $token =Str::random(32);
            $data=[
                "token"=>$token,
                "user_id"=>$user->user_id,
                "time"=>time()
            ];
            //token入库
            TokenModel::create($data);
            $response=[
                "error"=>"1",
                "msg"=>"登录成功",
                "token"=>$token
            ];

            $key = 'c:view_count:'.$user->user_id;
            $url=$_SERVER["REQUEST_URI"];
            if(strpos($url,'?')){
                $url=substr($url,0,strpos($url,'?'));
            }
            Redis::hincrby($key,$url,1);
        }else{
            $response=[
                "error"=>"40006",
                "msg"=>"登录失败"
            ];
        }
        return $response;
    }
    //首页
    public function conter()
    {
        //获取token
        $token = request()->get("token");
        //查看token是否和库中一样
        $data=TokenModel::where(["token"=>$token])->first();
        //签到
        $res ="qiandao";
        Redis::zincrby($res,time(),$data["user_id"]);
        //判断token正确进去
        if($data){
            $response=[
                "error"=>"2",
                "msg"=>"欢迎来到H5商城"
            ];
            return $response;
        }
    }
}
