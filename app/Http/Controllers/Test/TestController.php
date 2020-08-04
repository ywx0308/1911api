<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Model\TokenModel;
use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    //获取token3种方式练习
    public function getwxtoken(){

//                $appid= "wx3604d90bd402abcb";
//                $appsecret="6bed0f1793738df4c16e369f4bb411a0";
//                $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
//                $data=file_get_contents($url);
//                echo $data;
    }
    public function getwxtoken2(){
//        $appid= "wx3604d90bd402abcb";
//        $appsecret="6bed0f1793738df4c16e369f4bb411a0";
//        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
//
//        // 创建一个新cURL资源
//        $ch = curl_init();
//
//        // 设置URL和相应的选项
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//
//        // 抓取URL并把它传递给浏览器
//        $res=curl_exec($ch);
//
//        // 关闭cURL资源，并且释放系统资源
//        curl_close($ch);
//        echo $res;
    }
    public function getwxtoken3(){
//        $appid= "wx3604d90bd402abcb";
//        $appsecret="6bed0f1793738df4c16e369f4bb411a0";
//        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
//
//        //实例化客户端
//        $client = new Client();
//
//        //get请求
//        $res = $client->request('GET', $url);
//
//        //返回状态码
//        echo $res->getStatusCode();
//
//        //连贯操作
//        $res = $client->request('GET', $url)->getBody()->getContents();
//        echo $res;
    }
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
    //对称解密
    public function decrypt(){
        $enc_data =file_get_contents("php://input");//接受加密后的密文
        $enc = base64_decode($enc_data);//解密方法
        $method = "AES-256-CBC";
        $key = "1911_api";
        $iv = "aaaabbbbccccdddd";
        $res = openssl_decrypt($enc,$method,$key,OPENSSL_RAW_DATA,$iv);
        echo $res;
    }
    //非对称解密和返回
    public function no_decrypt(){
        $enc_data =file_get_contents("php://input");//接受加密后的密文
        $content = file_get_contents(storage_path("key/1911_pub.key"));//获取公钥内容
        $pub_key = openssl_get_publickey($content);//获取公钥
        openssl_public_decrypt($enc_data,$enc_data,$pub_key);//公钥解密

        //echo $enc_data;die;
        //返回加密
        $data = "猪已杀,肉已顿,过来大口吃肉，大碗喝酒";
        $content = file_get_contents(storage_path("key/api_priv.key"));//获取私钥内容
        $pub_key = openssl_get_privatekey($content);//获取私钥
        openssl_private_encrypt($data,$enc_data,$pub_key);//私钥加密
        echo $enc_data;
    }
    //签名验签
    public function name2(Request $request){
        $key = "api_1911";
        $name = $request->get("name");
        $sign = $request->get("data");
        $signs = md5($key.$name);
        if($sign==$signs){
            echo "签名验证成功";
        }else{
            echo "签名验证失败";
        }
    }
    //签名非对称解密
    public function nam_decrypt(){
        $content = request()->get("content");
        $data = request()->get("data");
        $enc = base64_decode($data);
        $con = file_get_contents(storage_path("key/1911_pub.key"));
        $pub_key = openssl_get_publickey($con);
        $res = openssl_verify($content,$enc,$pub_key);
        if($res){
            echo "验签成功";
        }else{
            echo "验签失败";
        }

    }
    //文件上传
    public function file(){
        return view("test.file");
    }
    public function upload(Request $request){
        if ($request->isMethod('POST')){
            $file = $request->file('source');
            //判断文件是否上传成功
            if ($file->isValid()){
                //原文件名
                $originalName = $file->getClientOriginalName();
                //扩展名
                $ext = $file->getClientOriginalExtension();
                //MimeType
                $type = $file->getClientMimeType();
                //临时绝对路径
                $realPath = $file->getRealPath();
                $filename = uniqid().'.'.$ext;
                $bool = Storage::disk('publics')->put($filename,file_get_contents($realPath));
                //判断是否上传成功
                if($bool){
                    echo 'success';
                }else{
                    echo 'fail';
                }
            }
        }
    }
}
