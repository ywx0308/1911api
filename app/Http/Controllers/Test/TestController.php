<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Model\TokenModel;
use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    //注册

    public function reg(Request $request){
        $user_name=$request->post("user_name");
        $user_email= $request->post("email");
        $user_pwd = $request->input("user_pwd");
        $user_pwd2 = $request->input("user_pwd2");
        if(empty($user_name)){
            $response=[
                "error"=>"40001",
                "msg"=>"用户名不能为空"
            ];
            return $response;
        }
        if(empty($user_email)){
            $response=[
                "error"=>"40002",
                "msg"=>"邮箱不能为空"
            ];
            return $response;
        }
        if(empty($user_pwd)){
            $response=[
                "error"=>"40003",
                "msg"=>"密码不能为空"
            ];
            return $response;
        }
        if($user_pwd!=$user_pwd2){
            $response=[
                "error"=>"40004",
                "msg"=>"密码不一致"
            ];
            return $response;
        }

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
        $name = request()->input("user_name");
        $pwd = request()->input("user_pwd");
        $user=UserModel::where(["user_name"=>$name])->first();
        if(empty($name)){
            $response=[
                "error"=>"40001",
                "msg"=>"用户名不能为空"
            ];
            return $response;
        }
        if(empty($pwd)){
            $response=[
                "error"=>"40003",
                "msg"=>"密码不能为空"
            ];
            return $response;
        }
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
            $res = TokenModel::create($data);
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

    //个人中心

    public function conter()
    {
        //获取token
        $token = request()->get("token");
        //黑名单
        $res1="blake";

        //查看token是否和库中一样
        $data=TokenModel::where(["token"=>$token])->first();
        //签到
        $res ="qiandao";
        Redis::zincrby($res,time(),$data["user_id"]);
        //判断token正确进去个人中心
        if($data){
            $response=[
                "error"=>"2",
                "msg"=>"欢迎来到个人中心"
            ];
            return $response;
        }
    }

    //redis哈希练习

    public function hash(){
        $data=[
            "name"=>"zhangyi",
            "age"=>18,
            "class"=>"1911班"
        ];
        $tom="boy";
        Redis::hmset($tom,$data);
    }
    public function hash1(){
        $tom="boy";
        $res=Redis::hgetall($tom);
        return $res;

    }

}
