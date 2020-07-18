<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Model\TokenModel;
use App\Model\UserModel;

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
                "error"=>"0",
                "msg"=>"登录成功",
                "token"=>$token
            ];
        }else{
            $response=[
                "error"=>"40006",
                "msg"=>"登录失败"
            ];
        }
        return $response;
    }
    public function conter()
    {
        $token = request()->get("token");
        if(empty($token)){
            $response=[
                "error"=>"40007",
                "msg"=>"请输入token"
            ];
            return $response;
        }
        $data=TokenModel::where(["token"=>$token])->first();
        if($data){
            $response=[
                "error"=>"0",
                "msg"=>"欢迎来到个人中心"
            ];
            return $response;
        }else{
            $response=[
                "error"=>"40009",
                "msg"=>"请输入正确token"
            ];
            return $response;
        }
    }

}
