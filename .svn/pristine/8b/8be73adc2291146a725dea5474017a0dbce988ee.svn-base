<?php 
namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

class TongLianOrderModel extends Model {
    protected $table = 'tonglian_order';
    public $timestamps = false;
     

    public static function addOrder($order_no,$order_date,$total_money,$channel_id,$remark,$pay_state){
    	$data = array(
    		"order_no"=>$order_no,
    		"order_date"=>$order_date,
    		"total_money"=>$total_money,
    		"channel_id"=>$channel_id,
    		"remark"=>$remark,
    		"pay_state"=>$pay_state,
            "create_date"=>time());

    	return DB::table("tonglian_order")->insertGetId($data);
    }

    public static function updateOrder($order_no,$pay_state,$pay_msg,$sign){
        DB::table('tonglian_order')
                    ->where('order_no', $order_no)
                    ->update(array('pay_state' => $pay_state,"pay_msg"=>$pay_msg,"sign"=>$sign));
        return true;
    }

    public static function getOrderInfo($order_no){
        $orderInfo = DB::table('tonglian_order')
                        ->select("id")
                        ->where('order_no', $order_no)
                        ->first();
      return $orderInfo;
    }

}