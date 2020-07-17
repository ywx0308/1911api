<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
<<<<<<< HEAD
use App\Model\UserModel;
=======
>>>>>>> 163895baf36f06bcdc34bf512a69275910090bca

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
<<<<<<< HEAD
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
=======
    public function getwxtoken2(){
        $appid= "wx3604d90bd402abcb";
        $appsecret="6bed0f1793738df4c16e369f4bb411a0";
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
>>>>>>> 163895baf36f06bcdc34bf512a69275910090bca

        // 创建一个新cURL资源
                $ch = curl_init();

        // 设置URL和相应的选项
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // 抓取URL并把它传递给浏览器
                $res=curl_exec($ch);

        // 关闭cURL资源，并且释放系统资源
                curl_close($ch);
                echo $res;
    }
    public function getwxtoken3(){
        $appid= "wx3604d90bd402abcb";
        $appsecret="6bed0f1793738df4c16e369f4bb411a0";
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;

        //实例化客户端
        $client = new Client();

        //get请求
        $res = $client->request('GET', $url);

        //返回状态码
        echo $res->getStatusCode();

        //连贯操作
        $res = $client->request('GET', $url)->getBody()->getContents();
        echo $res;
    }


    public function apiinit(){
        $url="http://www.api.com/test/getwxtoken";
        $request=file_get_contents($url);
        echo $request;
    }

}
