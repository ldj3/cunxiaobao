<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

class EntryOrderLogistics extends Model {
    protected $table = 'biz_order_logistics';
    public $timestamps = false;


    public static function saveOrderLogistics($enterpriseId,$shopId,$createName,$customer_name,$customer_mobile,$province,$city,$area,$address,$order_id,$express_order,$operator,$operator_mobile,$operator_name,$express_code,$express_name,$state,$remark,$state_remark){
    	$time = get_ms();
        $maxVersion = max_version('biz_order_logistics',$enterpriseId) + 1;
		$id = get_newid('biz_order_logistics');

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
          'customer_name'=>$customer_name, //客户ID
          'customer_mobile'=>$customer_mobile,
          'province'=>$province,
          'city'=>$city,
          'area'=>$area,
          'address'=>$address,
          'order_id'=>$order_id,
          'express_order'=>$express_order,//快递单号
          'operator'=>$operator, //经手人ID(备用)
          'operator_mobile'=>$operator_mobile, //经手人电话
          'operator_name'=>$operator_name, //经手人姓名
          'express_code'=>$express_code,  //快递类型
          'express_name'=>$express_name,	//快递名称
          'state'=>$state, //状态 （1=未发货,2=已发货，3=已收货）
          'remark'=>$remark, //发货备注
          'state_remark'=>$state_remark //签收备注
          );
        //print_r($data);
        DB::table("biz_order_logistics")->insert($data);
        return $id;

    }
    
    /**
     * 客户发货物流信息
     * @param logisticsId 物流Id
     * @return object
     */
    public function findLogisticsById($logisticsId) {
        $rt = DB::table("biz_order_logistics as bol")
                ->join("biz_customer_consignee as bcc","bol.consignee_id","=","bcc.id")
                ->select("bol.create_date as createDate","bol.modify_date as modifyDate","bol.creator","bol.editor","bol.enterprise as enterpriseId","bol.shop as shopId","bol.order_id as orderId","bol.express_order as expressOrderNum","bol.operator as operatorId","bol.operator_mobile as operatorMobile","bol.operator_name as operatorName","bol.express_code as expressCode","bol.express_name as expressName","bol.state as logisticsState","bol.remark as logisticsRemark","bol.state_remark as stateRemark","bol.consignee_id as consigneeId","bcc.customer_id as customerId","bcc.customer_name as customerName","bcc.customer_mobile as customerMobile","bcc.province","bcc.city","bcc.area","bcc.address","bcc.postalcode")
                ->where("bol.id",$logisticsId)
                ->first();
        return $rt;
    }
}