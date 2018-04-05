<?php namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
class EntrySetter extends Model {
    protected $table = 'attribute';
    public $timestamps = false;
    
    
    //系统配置查询
    static function findAttributeByShopId($shopId){
        $attribute = DB::table("attribute")
                       ->where("shop_id",$shopId)
                       ->first();
        $attribute = obj2arr($attribute);
        if($attribute){
            return $attribute;
        }else{
            return false;
        }
    }
    //修改属性
    static function updateAttribute($arr,$shopId){
        $rt = DB::table('attribute')
                ->where('shop_id', $shopId)
                ->update($arr);
        if($rt){
            return true;
        }else{
            return false;
        }
   }
}