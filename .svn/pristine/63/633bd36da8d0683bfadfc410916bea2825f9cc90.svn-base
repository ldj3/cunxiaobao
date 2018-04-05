<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryWarehouseInOut extends Model {
    protected $table = 'base_warehouse_inout';
    public $timestamps = false;
    
    //仓库调拨列表
    function allocationList($arr){
        $query = DB::table("biz_warehouse_inout as bwi")
                   ->select("bwi.id","bwi.inout_no as inoutNo","bwi.order_date as orderDate","bwi.operator_name as operatorName","bwi.total_qty as totalQty","bwi.remark","bwi.status","bw.warehouse_name as inWarehouseName","bwo.warehouse_name as outWarehouseName")
                   ->leftJoin("base_warehouse as bw","bwi.in_warehouse_id","=","bw.id")
                   ->leftJoin("base_warehouse as bwo","bwi.out_warehouse_id","=","bwo.id")
                   ->where("bwi.is_deleted",0)
                   ->where("bwi.enterprise",$arr["enterpriseId"])
                   ->where("bwi.shop",$arr["shopId"]);
        if(!empty($arr["keyword"])){
            $this->kw = changeStr($arr["keyword"]);
            $query->where(function($sql){
                $sql->where("bwi.inout_no","like",$this->kw)
                    ->orWhere("bw.warehouse_name","like",$this->kw)
                    ->orWhere("bwo.warehouse_name","like",$this->kw)
                    ->orWhere("bwi.operator_name","like",$this->kw);
            });
        }
        if(!empty($arr["startDate"])){
            $query->where("bwi.order_date",">=",$arr["startDate"])->where("bwi.order_date","<=",$arr["endDate"]);
        }
        $total = $query->count();
        
        $warehouseInout = $query->orderBy("bwi.create_date", "desc")
                                ->skip($arr["offset"])
                                ->take($arr["rows"])
                                ->get();
        $warehouseInout = obj2arr($warehouseInout);

        $this->warehouse_inout_type = Config::get("app.warehouse_inout_type");
        
        for($i = 0;$i < count($warehouseInout);$i++){
            $warehouseInout[$i]["orderDate"] = date("Y-m-d H:i:s",$warehouseInout[$i]["orderDate"]/1000);
            $warehouseInout[$i]["totalQty"] = doubleval($warehouseInout[$i]["totalQty"]);
            $warehouseInout[$i]["status"] = $this->warehouse_inout_type[$warehouseInout[$i]["status"]];
        }
        
        $rt = array();
        $rt["data"] = $warehouseInout;
        $rt["total"] = $total;
        
        return $rt;
    }
    
    //调拨详情
    function findAllocation($arr){
        $customer = new EntryCustomer;
        $goods = new EntryGoods;
        $allocationInfo = DB::table("biz_warehouse_inout as bwi")
                            ->select("bwi.inout_no as inOutNo","bwi.out_warehouse_id as outWarehouseId","bwi.in_warehouse_id as inWarehouseId","bwi.status","bwi.operator_name as operatorName","bwi.order_date as orderDate","bwi.remark","bw.warehouse_name as inWarehouseName","bwo.warehouse_name as outWarehouseName","bwi.total_qty as totalQty")
                            ->leftJoin("base_warehouse as bw","bwi.in_warehouse_id","=","bw.id")
                            ->leftJoin("base_warehouse as bwo","bwi.out_warehouse_id","=","bwo.id")
                            ->where("bwi.enterprise",$arr["enterpriseId"])
                            ->where("bwi.shop",$arr["shopId"])
                            ->where("bwi.id",$arr["allocationId"])
                            ->first();
        $return = array();
        if($allocationInfo){
            $allocationInfo->orderDate = date("Y-m-d H:i:s",($allocationInfo->orderDate)/1000);
            $allocationInfo->totalQty = doubleval($allocationInfo->totalQty );
            $this->order_status = Config::get("app.order_status");
            $allocationInfo->status = $this->order_status[$allocationInfo->status];
            $detail = DB::table("biz_warehouse_inout_dtl")
                        ->select("id","warehouse_inout_id as warehouseInoutId","qty","remark","unit","in_batch_id as inBatchId","out_batch_id as outBatchId","goods as goodsId","dimensional_id as dimensionalId")
                        ->where("warehouse_inout_id",$arr["allocationId"])
                        ->get();
            
            for($i = 0;$i < count($detail);$i++){
                $detail[$i]->qty = doubleval($detail[$i]->qty);
                
                $query = DB::table("biz_goods_unit_info")
                           ->select("barcode","barcode1")
                           ->where("goods",$detail[$i]->goodsId);
                if(($detail[$i]->dimensionalId) != null || ($detail[$i]->dimensionalId) != ""){
                    $query->where("dimensional_id",$detail[$i]->dimensionalId);
                }
                $barcode = $query->first();
                $detail[$i]->barcode = $barcode->barcode;
                $detail[$i]->barcode1 = $barcode->barcode1;
                
                $goodsInfo = $goods->find($detail[$i]->goodsId,array("name as goodsName","image","description","property1","property2","property3","property_title1 as propertyTitle1","property_title2 as propertyTitle2"));
                $detail[$i]->goodsName = $goodsInfo->goodsName;
                $detail[$i]->property1 = $goodsInfo->property1;
                $detail[$i]->property2 = $goodsInfo->property2;
                $detail[$i]->property3 = $goodsInfo->property3;
                $detail[$i]->propertyTitle1 = $goodsInfo->propertyTitle1;
                $detail[$i]->propertyTitle2 = $goodsInfo->propertyTitle2;
                $detail[$i]->image = $goodsInfo->image;
                
                if($detail[$i]->dimensionalId){
                    $arrDim = DB::table("base_goods_dimensional")
                                ->select("property_value1 as propertyValue1","property_value2 as propertyValue2","image","qty")
                                ->where("id",$detail[$i]["dimensionalId"])
                                ->first();
                    if($arrDim){
                        $detail[$i]->propertyValue1 = $arrDim->propertyValue1;
                        $detail[$i]->propertyValue2 = $arrDim->propertyValue2;
                        $detail[$i]->image = $arrDim->image;
                    }
                }
                if(!$detail[$i]->image){
                    $detail[$i]->image = Config::get("app.url").Config::get("app.goods_default_pic");
                }
            }
            $return["data"] = $allocationInfo;
            $return["detail"] = $detail;
        }
        
        return $return;
    }
}