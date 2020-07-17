<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Model\UserModel;

class TestController extends Controller
{
    public function reg(){
        $user_name= request()->post("user_name");
        $user_email= request()->post("email");
        $user_pwd1= request()->post("user_pwd");
        $user_pwd2= request()->post("user_pwd2");
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
        if($user_pwd1 != $user_pwd2){
            $response=[
                "error"=>"40004",
                "msg"=>"密码不一致"
            ];
            return $response;
        }

        $user_pwd=password_hash($user_pwd1,PASSWORD_DEFAULT);
        $data=[
            "user_name" => $user_name,
            "email"     => $user_email,
            "user_pwd" =>  $user_pwd
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
    public function login(){
        $name = request()->inout("user_name");
        $pwd = request()->input("user_pwd");
        $user=UserModel::where(["user_name"=>$name])->first();
        if($user){
            $pwd = password_verify($pwd,$user->user_pwd);
            $response=[
                "error"=>"0",
                "msg"=>"登录成功"
            ];
        }else{
            $response=[
                "error"=>"40006",
                "msg"=>"登录失败"
            ];
        }
        return $response;
    }
    public function conter(){

    }

}
