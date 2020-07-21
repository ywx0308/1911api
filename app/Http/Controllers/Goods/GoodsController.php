<?php

namespace App\Http\Controllers\Goods;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\GoodsModel as Goods;
use Illuminate\Support\Facades\Redis;
class GoodsController extends Controller
{
    public function info(){
        $goods_id=request()->get("goods_id");
        $key="info";
        $goods_info=Redis::hgetall($key);
        if(empty($goods_info)){
            echo "无缓存";
            $info=Goods::find($goods_id)->toArray();
            $goods=Redis::hmset($key,$info);
            print_r($goods);
        }else{
            echo "缓存";
            $info=Redis::hgetall($key);
            print_r($info);
        }
    }

}
