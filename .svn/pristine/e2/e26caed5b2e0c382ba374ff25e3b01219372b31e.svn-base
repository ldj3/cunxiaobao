<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

class EntryShop extends Model {
    protected $table = 'base_shop';
    public $timestamps = false;
    
    //店铺列表
    static function shopList($enterpriseId,$page,$rows){
        if(!$page){
            $page = 1;
        }
        if(!$rows){
            $rows = 100;
        }
        $offset = ($page-1)*$rows;
        $shops = DB::table("base_shop")
                   ->where("enterprise",$enterpriseId)
                   ->where("is_deleted",0)
                   ->skip($offset)
                   ->take($rows)
                   ->get();
        $shops = obj2arr($shops);
        if(empty($shops)){
            return false;
        }else{
            return $shops;
        }
    }

    //添加店铺
    public static function addShopInfo($enterpriseId,$shopname,$contact,$phone,$mobile,$industry,$address,$description){
      $shopId = create_uuid();
      $time = get_ms();

      $data = array(
        'id'=>$shopId,
        'is_deleted'=>0,
        'create_date'=>$time,
        'modify_date'=>$time,
        'version'=>1,
        'enterprise'=>$enterpriseId,
        'name'=>$shopname,
        'stock_type'=>'shopWarehouse',
        'share_goods'=>0,
        'contact'=>$contact,
        'phone'=>$phone,
        'mobile'=>$mobile,
        'industry'=>$industry,
        'address'=>$address,
        'description'=>$description);

      $count = DB::table('base_shop')->insert($data);
      return $shopId;

    }
	
	//获取店铺属性
	function getShopThreeAtt($shopId){
		$property = DB::table("attribute")
                      ->select("goods_property_one as goodsPropertyOne","goods_property_two as goodsPropertyTwo","goods_property_three as goodsPropertyThree")
                      ->where("shop_id",$shopId)
                      ->first();
		return $property;
	}
    
    //获取第一个店铺ID
    function findFirstShopId($enterpriseId){
        $shopId = DB::table("base_shop")
                    ->select("id")
                    ->where("enterprise",$enterpriseId)
                    ->orderBy("create_date")
                    ->first();
        $shopId = $shopId->id;
        return $shopId;
    }
    
    /** 
      * 函数名: getShopNameByShopId
      * 用途: 获取店铺名称
      *
      * @access public 
      * @param shopId 店铺ID
      * @return String
    */ 
    function getShopNameByShopId($shopId){
        $rt = DB::table("base_shop")
                ->where("id",$shopId)
                ->pluck("name");
        return $rt;
    }
}