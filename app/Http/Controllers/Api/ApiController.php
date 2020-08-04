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
    //沙箱支付
    public function alipay(){
        return view("api.alipay");
    }
    /**
     * 跳转支付宝支付
     */
    public function do_alipay(Request $request)
    {
        $oid = $request->get('oid');
        //echo '订单ID： '. $oid;
        //根据订单查询到订单信息  订单号  订单金额

        //调用 支付宝支付接口

        // 1 请求参数
        $param2 = [
            'out_trade_no'      => time().mt_rand(11111,99999),
            'product_code'      => 'FAST_INSTANT_TRADE_PAY',
            'total_amount'      => 0.01,
            'subject'           => '1911-测试订单-'.Str::random(16),
        ];

        // 2 公共参数
        $param1 = [
            'app_id'        => '2016102000727749',
            'method'        => 'alipay.trade.page.pay',
            'return_url'    => 'http://1911www.comcto.com/alipay/return',   //同步通知地址
            'charset'       => 'utf-8',
            'sign_type'     => 'RSA2',
            'timestamp'     => date('Y-m-d H:i:s'),
            'version'       => '1.0',
            'notify_url'    => 'http://1911www.comcto.com/alipay/notify',   // 异步通知
            'biz_content'   => json_encode($param2),
        ];

        //echo '<pre>';print_r($param1);echo '</pre>';
        // 计算签名
        ksort($param1);
        //echo '<pre>';print_r($param1);echo '</pre>';

        $str = "";
        foreach($param1 as $k=>$v)
        {
            $str .= $k . '=' . $v . '&';
        }

        $str = rtrim($str,'&');     // 拼接待签名的字符串

        $sign = $this->sign($str);
        echo $sign;echo '<hr>';

        //沙箱测试地址
        $url = 'https://openapi.alipaydev.com/gateway.do?'.$str.'&sign='.urlencode($sign);
        return redirect($url);
        //echo $url;
    }

    protected function sign($data)
    {
//        if ($this->checkEmpty($this->rsaPrivateKeyFilePath)) {
//            $priKey = $this->rsaPrivateKey;
//
//            $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
//                wordwrap($priKey, 64, "\n", true) .
//                "\n-----END RSA PRIVATE KEY-----";
//        } else {
//            $priKey = file_get_contents($this->rsaPrivateKeyFilePath);
//            $res = openssl_get_privatekey($priKey);
//        }

        $priKey = file_get_contents(storage_path('key/ali_priv.key'));
        $res = openssl_get_privatekey($priKey);
        var_dump($res);echo '<hr>';

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }

}
