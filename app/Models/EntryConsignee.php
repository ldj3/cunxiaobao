<?php namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
class EntryConsignee extends Model {
    protected $table = 'biz_customer_consignee';
    public $timestamps = false;
    
    /** 
      * consigneeList
      * 收货地址列表
      *
      * @access public 
      * @param customerId 客户ID，为空时为散户 
      * @return array 
    */ 
    public function consigneeList($customerId,$enterpriseId,$offset,$rows,$keyword){
        $sql = DB::table("biz_customer_consignee")
                 ->select("id as consigneeId","customer_id as customerId","customer_name as customerName","customer_mobile as customerMobile","province","city","area","address","postalcode")
                 ->where("is_deleted",0)
                 ->where("enterprise",$enterpriseId);
        if($customerId){
            $sql->where("customer_id",$customerId);
        }else{
            $sql->where(function($query){
                        $query->whereNull("customer_id")
                              ->orWhere("customer_id","");
                    });
        }
        $rt = $sql->orderBy("create_date","desc")
                  ->skip($offset)
                  ->take($rows)
                  ->get();
        return $rt;
    }
    
    /** 
      * addConsignee
      * 收货地址新增
      *
      * @access public 
      * @param customerName 收货人名称
      * @param customerMobile 收货人电话
      * @param province 省
      * @param city 市
      * @param area 区
      * @param address 地址
      * @param postalcode 邮编
      * @param operator 操作人
      * @param enterpriseId 企业ID
      * @param shopId 店铺ID
      * @return int 
    */ 
    public function addConsignee($customerId,$customerName,$customerMobile,$province,$city,$area,$address,$postalcode,$operator,$enterpriseId,$shopId){
        $arr["customer_id"] = $customerId;
        $arr["customer_name"] = $customerName;
        $arr["customer_mobile"] = $customerMobile;
        $arr["province"] = $province;
        $arr["city"] = $city;
        $arr["area"] = $area;
        $arr["address"] = $address;
        $arr["postalcode"] = $postalcode;
        
        $arr["id"] = get_newid("biz_customer_consignee");
        $arr["is_deleted"] = 0;
        $arr["create_date"] = get_ms();
        $arr["modify_date"] = get_ms();
        $arr["creator"] = $operator;
        $arr["editor"] = $operator;
        $arr["version"] = max_version("biz_customer_consignee",$enterpriseId)+1;
        $arr["enterprise"] = $enterpriseId;
        $arr["shop"] = $shopId;
        
        $rt = DB::table("biz_customer_consignee")->insert($arr);
        if($rt){
            $rtArr["consigneeId"] = $arr["id"];
            $rtArr["customerId"] = $arr["customer_id"];
            $rtArr["customerName"] = $arr["customer_name"];
            $rtArr["customerMobile"] = $arr["customer_mobile"];
            $rtArr["province"] = $arr["province"];
            $rtArr["city"] = $arr["city"];
            $rtArr["area"] = $arr["area"];
            $rtArr["address"] = $arr["address"];
            $rtArr["postalcode"] = $arr["postalcode"];
            return $rtArr;
        }else{
            return false;
        }
    }
    
    /** 
      * delConsignee
      * 收货地址删除
      *
      * @access public 
      * @param customerName 收货地址ID
      * @param operator 操作人名称
      * @return int 
    */ 
    public function delConsignee($consigneeId,$operator){
        $arr["modify_date"] = get_ms();
        $arr["editor"] = $operator;
        $arr["version"] = max_version("biz_customer_consignee",$enterpriseId)+1;
        $arr["is_deleted"] = 1;
        $rt = DB::table("biz_customer_consignee")
                ->where("id",$consigneeId)
                ->update($arr);
        return $rt;
    }
}