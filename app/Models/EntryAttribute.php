<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryAttribute extends Model {
    protected $table = 'attribute';
    public $timestamps = false;    

    public static function addAttribute($shopId,$industryInfo){
      $time = get_ms();
      $data = array(
        'shop_id'=>$shopId,
        'create_date'=>$time,
        'modify_date'=>$time,
        'price_scale'=>'2',
        'qty_scale'=>'2',
        'money_scale'=>'2',
        'order_low_discount'=>'70',
        'order_discount_memory'=>'0',
        'order_qty_memory'=>'0',
        'order_settle_type'=>'s',
        'goods_property_one'=>$industryInfo->property1,
        'goods_property_two'=>$industryInfo->property2,
        'goods_property_three'=>$industryInfo->property3,
        'date_range'=>'60'
        );

      $attributeId = DB::table('attribute')->insertGetId($data);
      return $attributeId;
    }
}