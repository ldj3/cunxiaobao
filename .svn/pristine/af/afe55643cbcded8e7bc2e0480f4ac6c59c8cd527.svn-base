<?php 
namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model {
    protected $table = 'order';
    public $timestamps = false;
      

    //商品列表
    public static function ordersList($channel_id,$keyword,$page,$rowNum){
      $orderList = DB::select("select o.id,o.order_no as orderNo,o.total_money as totalMoney,c.channel_name as channelName,o.is_deleted,o.remark,o.is_pay,cu.customer_name as customerName,cu.customer_mobile as customerMobile,FROM_UNIXTIME(order_date, '%Y-%m-%d %T' ) as orderDate,p.pay_name as payName from `order` as o,channel as c,customer as cu,pay_info p where c.id = o.channel_id and cu.id = o.customer_id and o.is_pay = p.id and o.channel_id = ".$channel_id." order by o.order_date DESC LIMIT ".($rowNum * ($page - 1)).",".$rowNum);
      return $orderList;
    }

    public static function addOrder($order_no,$order_date,$total_money,$customer_id,$channel_id,$remark,$is_pay){
    	$data = array(
    		"order_no"=>$order_no,
    		"order_date"=>$order_date,
    		"total_money"=>$total_money,
    		"customer_id"=>$customer_id,
    		"channel_id"=>$channel_id,
    		"remark"=>$remark,
    		"is_pay"=>$is_pay);

    	return DB::table("order")->insertGetId($data);
    }


}