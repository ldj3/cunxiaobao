<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryReceipt extends Model {
    protected $table = 'biz_receipt';
    public $timestamps = false;
        
    //收款列表
    function receiptList($arr){
        $query = DB::table("biz_receipt as br")
                   ->join("base_customer as bc",'br.customer', '=','bc.id')
                   ->leftJoin("base_enterprise_user as beu","br.operator","=","beu.id")
                   ->select("br.id","br.customer","br.receipt_no as receiptNo","br.status","br.receipt_money as receiptMoney","br.type","br.receipt_date as receiptDate","br.operator","br.remark","bc.name as customerName","beu.name as operatorName","br.editor")
                   ->where("br.is_deleted",0)
                   ->where("br.enterprise",$arr["enterpriseId"])
                   ->where("br.shop",$arr["shopId"]);
        if(!empty($arr["customer"])){
            $this->kw = changeStr($arr["customer"]);
            $query->where("bc.name","like",$this->kw);
        }
        if(!empty($arr["startDate"])){
            $arr["startDate"] = strtotime($arr["startDate"]." 00:00:00")*1000;
            $arr["endDate"] = strtotime($arr["endDate"]." 23:59:59")*1000;
            
            $query->where("br.receipt_date",">=",$arr["startDate"])->where("br.receipt_date","<=",$arr["endDate"]);
        }
        $total = $query->count();
        $receiptList = $query->orderBy("receipt_date","ace")->skip($arr["offset"])->take($arr["rows"])->get();
        $receiptList = obj2arr($receiptList);
        if(!empty($receiptList)){
            $this->receipt_type = Config::get("app.receipt_type");
            for($i = 0;$i < count($receiptList);$i++){
                $receiptList[$i]["receiptDate"] = date("Y-m-d H:i:s",$receiptList[$i]["receiptDate"]/1000);
                if(empty($receiptList[$i]["operatorName"])){
                    $receiptList[$i]["operatorName"] = "";
                }
                if(empty($receiptList[$i]["type"])){
                    $receiptList[$i]["type"] = "";
                }else{
                    $receiptList[$i]["type"] = $this->receipt_type[$receiptList[$i]["type"]];
                }
                $receiptList[$i]["receiptMoney"] = doubleval($receiptList[$i]["receiptMoney"]);
            }
        }
        $rt = array();
        $rt["total"] = $total;
        $rt["data"] = $receiptList;
        return $rt;
    }
    
    //客户收款列表
    function receiptListByCustomerId($arr){
        $receiptList = DB::table('biz_receipt')
                        ->select("id","receipt_date as receiptDate","type as receiptType","custom_no as customNo","receipt_money as receiptMoney")
                        ->where('is_deleted',0)
                        ->where('enterprise',$arr["enterpriseId"])
                        ->where('shop',$arr["shopId"])
                        ->where('customer',$arr["customerId"])
                        ->where("receipt_date", ">=",$arr["startDate"])
                        ->where("receipt_date", "<=",$arr["endDate"])
                        ->where("type",$arr["receiptType"])
                        ->orderBy("receipt_date", "desc")
                        ->skip($arr["offset"])
                        ->take($arr["rows"])
                        ->get();
        return $receiptList;
    }
    
    //生成收款流水订单号
    function getReceiptNo($prefix,$shortCode,$enterpriseId,$shopId,$today,$status){
        $startDate = strtotime(date('Y-m-d',time())." 00:00:00")*1000;
        $endDate = strtotime(date('Y-m-d',time())." 23:59:59")*1000;
        
        $receiptNum = DB::table("biz_receipt")
                        ->where("enterprise",$enterpriseId)
                        ->where("shop",$shopId)
                        ->where("status",$status)
                        ->where("create_date",">=",$startDate)
                        ->where("create_date","<=",$endDate)
                        ->count();
        if($receiptNum < 10){
            $receiptNo = "000".$receiptNum;
        }else if($receiptNum < 100){
            $receiptNo = "00".$receiptNum;
        }else if($receiptNum < 1000){
            $receiptNo = "0".$receiptNum;
        }else{
            $receiptNo = $receiptNum;
        }
        
        $rt = $prefix.$today.$shortCode.$receiptNo;
        return $rt;
    }
    
    //新增流水
    function addReceipt($data){
        $rt = DB::table("biz_receipt")
                ->insert($data);
        return $rt;
    }
    
}