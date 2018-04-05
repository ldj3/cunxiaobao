<?php 
namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

class OrderDtlModel extends Model {
    protected $table = 'order_dtl';
    public $timestamps = false;
      

    

    public static function addOrderDtl($order_id,$goods_id,$price,$qty,$money,$remark){
    	$data = array(
    		"order_id"=>$order_id,
    		"goods_id"=>$goods_id,
    		"price"=>$price,
    		"qty"=>$qty,
    		"money"=>$money,
    		"remark"=>$remark);

    	DB::table("order_dtl")->insert($data);
    }


}