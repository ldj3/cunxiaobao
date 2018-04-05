<?php 
namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

class GoodsModel extends Model {
    protected $table = 'goods';
    public $timestamps = false;
      

    //商品列表
    public static function goodsList(){
      $goodsList = DB::table("goods as g")
                  ->leftJoin('channel as c', 'c.id', '=', 'g.channel_id')
                  ->select("g.id","g.goods_name as goodsName","g.price","g.describe","c.channel_name as channelName","g.is_deleted","g.date")
                  ->orderBy('g.date','desc')
                  ->get();
      return $goodsList;
    }

    public static function addGoods($goodsName,$price,$describe,$channel_id){
            $data = array(
          "goods_name"=>$goodsName,
          "price"=>$price,
          "describe"=>$describe,
          "channel_id"=>$channel_id,
          "date"=>time());
           
        DB::table("goods")->insert($data);
    }

    public static function getGoods($id){
      $goodsList = DB::table("goods")
                  ->select("id","goods_name as goodsName","price","describe")
                  ->where("id",$id)
                  ->first();
      return $goodsList;
    }
}