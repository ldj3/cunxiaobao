<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

class EntryOrderDtl extends Model {
    protected $table = 'biz_order_dtl';
    public $timestamps = false;


    //保存订单信息
    public static function saveOrderDtlInfo($orderId,$enterpriseId,$shopId,$createName,$goodsId,$sal_qty,$sal_price,$cost_price,$money,$is_stack,$dimensional_id,$warehouse_id,$remark,$goods_batch_id,$goods_unit_id,$unit_sort,$sal_unit,$maxVersion){
        $time = get_ms();
        $id = get_newid('biz_order_dtl');

        $data = array(
          'id'=>$id,
          'is_deleted'=>0,
          'create_date'=>$time,
          'modify_date'=>$time,
          'enterprise'=>$enterpriseId,
          'shop'=>$shopId,
          'creator'=>$createName,
          'editor'=>$createName,
          'version'=>$maxVersion,

          'order'=>$orderId, //客户ID
          'goods'=>$goodsId,
          'sal_qty'=>$sal_qty,
          'sal_price'=>$sal_price,
          'cost_price'=>$cost_price,
          'money'=>$money,
          'is_stack'=>$is_stack,
          'dimensional_id'=>$dimensional_id,
          'warehouse_id'=>$warehouse_id,

          'goods_batch_id'=>$goods_batch_id,
          'goods_unit_id'=>$goods_unit_id,
          'unit_sort'=>$unit_sort,
          'sal_unit'=>$sal_unit,
          'remark'=>$remark
          );
        //print_r($data);
        DB::table("biz_order_dtl")->insert($data);
        return $id;
    }

}