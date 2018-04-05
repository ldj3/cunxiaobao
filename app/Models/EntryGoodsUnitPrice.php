<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryGoodsUnitPrice extends Model {
    protected $table = 'biz_goods_unit_info';
    public $timestamps = false;    

    public function addGoodsUnitPrice($enterpriseId,$shopId,$createName,$maxVersion=0,$goods,$dimensional_id='',$goods_unit_id,$unit,$unit_sort=1,$uplimit_qty=0,$downlimit_qty=0,$limit_price=0,$retail_price=0,$retail_price1=0,$trade_price=0,$cost_price=0,$barcode='',$barcode1=''){
        //echo "addGoodsUnitPrice";
        if($maxVersion == 0){
            $maxVersion = max_version('biz_goods_unit_info',$enterpriseId)+1;
        }
        $id = create_uuid();
        $time = get_ms();
        $data = array(
            'id'=> $id,
            'is_deleted'=> 0,
            'create_date'=> $time,
            'modify_date'=> $time,
            'enterprise'=> $enterpriseId,
            'shop'=> $shopId,
            'creator'=> $createName,
            'editor'=> $createName,
            'version'=> $maxVersion,
            'goods'=> $goods,
            'dimensional_id'=> $dimensional_id,
            'goods_unit_id'=> $goods_unit_id,
            'unit'=> $unit,
            'unit_sort'=> $unit_sort,
            'uplimit_qty'=> $uplimit_qty,
            'downlimit_qty'=> $downlimit_qty,
            'limit_price'=> $limit_price,
            'retail_price'=> $retail_price,
            'retail_price1'=> $retail_price1,
            'trade_price'=> $trade_price,
            'cost_price'=> $cost_price,
            'barcode'=> $barcode,
            'barcode1'=> $barcode1
            );
        //print_r($data);
        DB::table('biz_goods_unit_info')->insert($data);
        return $id;
    }
    
    //修改单位价格信息
    public function updateUnitPrice($arr){
        $arr["modify_date"] = get_ms();
        $arr["version"] = max_version("biz_goods_unit_info",$arr["enterprise"])+1;
        $rt = DB::table("biz_goods_unit_info")
                ->where("id",$arr["id"])
                ->update($arr);
        return $rt;
    }
    
    //新增单位价格信息
    public function addUnitPrice($arr){
        $rt = DB::table("biz_goods_unit_info")
                ->insert($arr);
        return $rt;
    }
}