<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryWarehouse extends Model {
    protected $table = 'base_warehouse';
    public $timestamps = false;
    
    //仓库列表
    static function warehouseList($enterpriseId,$page,$rows){
        $offset = ($page-1)*$rows;
        $query = DB::table("base_warehouse")
                   ->select("id","warehouse_name as warehouseName","warehouse_code as warehouseCode","is_enable as isEnable","mobile","code","contacts","email","address","remark","is_default as isDefault")
                   ->where("enterprise",$enterpriseId);
        $total = $query->count();
        
        $warehouseList = $query->skip($offset)
                               ->take($rows)
                               ->get();
        $warehouseList = obj2arr($warehouseList);
        $rt = array();
        $rt["total"] = $total;
        $rt["data"] = $warehouseList;
        return $rt;
    }
    
    //已使用仓库列表
    static function warehouseIdList($enterpriseId,$shopId){
        $warehouseId = DB::table("biz_warehouse_permission as bwp")
                         ->join("base_warehouse as bw","bwp.warehouse_id","=","bw.id")
                         ->where("bwp.enterprise",$enterpriseId)
                         ->where("bwp.shop",$shopId)
                         ->where("bwp.is_deleted",0)
                         ->where("bw.is_enable",1)
                         ->distinct("bwp.warehouse_id")
                         ->lists("bwp.warehouse_id");
        return $warehouseId;
    }
    
    //根据商品ID查询库存信息查询总库存
    static function findQtyByGoodsId($goodsId,$warehouseId){
        $goodsQty = DB::table("biz_warehouse_relation")
                      ->select("warehouse_id as warehouseId","qty","goods_unit_id as goodsUnitId","unit","unit_sort as unitSort")
                      ->where("goods",$goodsId)
                      ->whereIn("warehouse_id",$warehouseId)
                      ->orderBy("unit_sort")
                      ->get();
        $goodsQty = obj2arr($goodsQty);
        $totalQty = "";
        $unit = array();
        for($i = 0;$i < count($goodsQty);$i++){
            $goodsQty[$i]["qty"] = doubleval($goodsQty[$i]["qty"]);
            if($i==0){
                $unit[0]["unit"] = $goodsQty[$i]["unit"];
                $unit[0]["qty"] = $goodsQty[$i]["qty"];
                $unit[0]["goodsUnitId"] = $goodsQty[$i]["goodsUnitId"];
            }else{
                $newArr = 0;
                for($j = 0;$j < count($unit);$j++){
                    if($goodsQty[$i]["goodsUnitId"] == $unit[$j]["goodsUnitId"]){
                        $unit[$j]["qty"] = $unit[$j]["qty"] + $goodsQty[$i]["qty"];
                        $newArr = 1;
                    }
                }
                if(!$newArr){
                    $num = count($unit);
                    $unit[$num]["unit"] = $goodsQty[$i]["unit"];
                    $unit[$num]["qty"] = $goodsQty[$i]["qty"];
                    $unit[$num]["goodsUnitId"] = $goodsQty[$i]["goodsUnitId"];
                }
            }
        }
        if(count($unit)){
            for($k = 0;$k < count($unit);$k++){
                $totalQty = $unit[$k]["qty"].$unit[$k]["unit"].$totalQty;
            }
        }else{
            $unitInfo = DB::table("biz_goods_unit_info")
                          ->where("goods",$goodsId)
                          ->where("unit_sort",1)
                          ->pluck("unit");
            $totalQty = "0".$unitInfo;
        }
        return $totalQty;
    }

    public function getUserWarehouse($enterpriseId,$shopId,$userId){
      $warehouseList = DB::table('biz_warehouse_permission as wp')
              ->leftJoin('base_warehouse as wa','wp.warehouse_id','=','wa.id')
              ->select('wa.id','wa.warehouse_name','wa.warehouse_code','wa.is_enable','wa.contacts','wa.mobile','wa.email','wa.address',
                'wa.remark','wa.is_default','wa.type')
              ->where('wp.enterprise',$enterpriseId)
              ->where('wp.shop_id',$shopId)
              ->where('wp.user_id',$userId)
              ->where('wp.is_deleted',0)
              ->get();
              return $warehouseList;
    }

    public static function getWarehouseList($enterpriseId){
      $warehouseList = DB::table('base_warehouse as wa')
              ->select('wa.id','wa.warehouse_name','wa.warehouse_code','wa.is_enable','wa.contacts','wa.mobile','wa.email','wa.address',
                'wa.remark','wa.is_default','wa.type')
              ->where('wa.enterprise',$enterpriseId)
              ->get();
              return $warehouseList;
    }
	
	//商品库存总数
	function sumGoodsStock($enterpriseId,$shopId){
		$sumGoodsStock = DB::table("biz_warehouse_relation")
						   ->where("enterprise",$enterpriseId)
						   ->sum("qty");
		return $sumGoodsStock;
	}
	
	//商品库存总库存与总成本
	function sumGoodsCost($enterpriseId){
		//一维查询
		$sql = '
				select IFNULL(sum(bwr.qty * bgui.cost_price),0) as sumCostPrice,IFNULL(sum(bwr.qty),0) as sumQty
					from biz_warehouse_relation as bwr
					join biz_goods_unit_info as bgui on bwr.goods = bgui.goods
					where bwr.enterprise = "'.$enterpriseId.'"
					and bwr.is_deleted = "0"
                    and bwr.goods_unit_id = bgui.goods_unit_id
					and bwr.dimensional_goods_id is null
				';
		$arr = DB::select($sql);
		$arr = obj2arr($arr);
		$sumGoods1 = $arr[0];
		$sumGoods1["sumCostPrice"] = doubleval($sumGoods1["sumCostPrice"]);
		$sumGoods1["sumQty"] = doubleval($sumGoods1["sumQty"]);
		
		//二维
		$query = '
				select IFNULL(sum(bwr.qty * bgui.cost_price),0) as sumCostPrice,IFNULL(sum(bwr.qty),0) as sumQty
					from biz_warehouse_relation as bwr
					join biz_goods_unit_info as bgui on bwr.goods = bgui.goods
					where bwr.enterprise = "'.$enterpriseId.'"
					and bwr.is_deleted = "0"
                    and bwr.goods_unit_id = bgui.goods_unit_id
					and bwr.dimensional_goods_id = bgui.dimensional_id
				';
		$arra = DB::select($query);
		$arra = obj2arr($arra);
		$sumGoods2 = $arra[0];
		$sumGoods2["sumCostPrice"] = doubleval($sumGoods2["sumCostPrice"]);
		$sumGoods2["sumQty"] = doubleval($sumGoods2["sumQty"]);
		
		$sumGoods["sumCostPrice"] = $sumGoods1["sumCostPrice"] + $sumGoods2["sumCostPrice"];
		$sumGoods["sumQty"] = $sumGoods1["sumQty"] + $sumGoods2["sumQty"];
		return $sumGoods;
	}
	
    //仓库调拨列表
    function allocationList($arr){
        $query = DB::table("biz_warehouse_inout as bwi")
                   ->select("bwi.id","bwi.inout_no as inoutNo","bwi.order_date as orderDate","bwi.operator_name as operatorName","bwi.total_qty as totalQty","bwi.remark","bwi.status","bw.warehouse_name as inWarehouseName","bwo.warehouse_name as outWarehouseName")
                   ->join("base_warehouse as bw","bwi.in_warehouse_id","=","bw.id")
                   ->join("base_warehouse as bwo","bwi.out_warehouse_id","=","bwo.id")
                   ->where("bwi.enterprise",$arr["enterpriseId"])
                   ->where("bwi.is_deleted",0)
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
        if(!empty($arr["inWarehouseId"])){
            $query->where("bwi.in_warehouse_id",$arr["inWarehouseId"]);
        }
        if(!empty($arr["outWarehouseId"])){
            $query->where("bwi.out_warehouse_id",$arr["outWarehouseId"]);
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
    
    //出入库流水
    function inOutList($arr){
        $inOutList = array();
        if(empty($arr)){
            return $inOutList;
        }else{          
            $query = DB::table("biz_inout_dtl as bid")
                       ->select("bid.id","bid.qty","bid.goods","bid.create_date as createDate","bid.mode","bid.goods","bg.name as goodsName","bgd.property_value1 as propertyValue1","bgd.property_value2 as propertyValue2")
                       ->join("base_goods as bg","bid.goods","=","bg.id")
                       ->leftJoin("base_goods_dimensional as bgd","bid.dimensional_id","=","bgd.id")
                       ->where("bid.is_deleted",0)
                       ->where("bid.enterprise",$arr["enterpriseId"])
                       ->where("bid.shop",$arr["shopId"]);
            if($arr["keyword"]){
                $arr["keyword"] = changeStr($arr["keyword"]);
                $query->where("bg.name","like",$arr["keyword"]);
            }
            $total = $query->count();
            $data = $query->orderBy("bid.create_date","desc")
                          ->skip($arr["offset"])
                          ->take($arr["rows"])
                          ->get();
            $data = obj2arr($data);
            $data = if_null($data);
            if(!empty($data)){
                $this->inout_type = Config::get("app.inout_type");
                for($i= 0;$i<count($data);$i++){
                    $data[$i]["qty"] = doubleval($data[$i]["qty"]);
                    $data[$i]["inOut"] = $data[$i]["qty"] > 0? "入库":"出库";
                    $data[$i]["qty"] = $data[$i]["qty"] > 0 ? $data[$i]["qty"] : abs($data[$i]["qty"]);
                    $data[$i]["createDate"] = date("Y-m-d H:i:s",$data[$i]["createDate"]/1000);
                    $data[$i]["mode"] = $this->inout_type[$data[$i]["mode"]];
                }
            }
            $inOutList["data"] = $data;
            $inOutList["total"] = $total;
            return $inOutList;
        }
    }
    
    //指定店铺下仓库列表
    function warehouseListByShop($enterpriseId,$shopId,$page,$rows){
        if(!$page){
            $page = 1;
        }
        if(!$rows){
            $rows = 1000;
        }
        $offset = ($page-1)*$rows;
        $warehouseList = DB::table("biz_warehouse_permission as bwp")
                           ->join("base_warehouse as bw","bwp.warehouse_id","=","bw.id")
                           ->select("bwp.warehouse_id as warehouseId","bw.warehouse_name as warehouseName")
                           ->where("bwp.enterprise",$enterpriseId)
                           ->where("bwp.shop",$shopId)
                           ->where("bwp.is_deleted",0)
                           ->where("bw.is_enable",1)
                           ->distinct("bwp.warehouse_id")
                           ->skip($offset)
                           ->take($rows)
                           ->get();
        return $warehouseList;
    }
    
    //根据商品ID查询库存信息查询总库存 ---- 二维
    static function findQtyByDimensionalId($goodsId,$dimensionalId,$warehouseId){
        $goodsQty = DB::table("biz_warehouse_relation")
                      ->select("warehouse_id as warehouseId","qty","goods_unit_id as goodsUnitId","unit","unit_sort as unitSort")
                      ->where("goods",$goodsId)
					  ->where("dimensional_goods_id",$dimensionalId)
                      ->whereIn("warehouse_id",$warehouseId)
                      ->orderBy("unit_sort")
                      ->get();
        $goodsQty = obj2arr($goodsQty);
        $totalQty = "";
        $unit = array();
        for($i = 0;$i < count($goodsQty);$i++){
            $goodsQty[$i]["qty"] = doubleval($goodsQty[$i]["qty"]);
            if($i==0){
                $unit[0]["unit"] = $goodsQty[$i]["unit"];
                $unit[0]["qty"] = $goodsQty[$i]["qty"];
                $unit[0]["goodsUnitId"] = $goodsQty[$i]["goodsUnitId"];
            }else{
                $newArr = 0;
                for($j = 0;$j < count($unit);$j++){
                    if($goodsQty[$i]["goodsUnitId"] == $unit[$j]["goodsUnitId"]){
                        $unit[$j]["qty"] = $unit[$j]["qty"] + $goodsQty[$i]["qty"];
                        $newArr = 1;
                    }
                }
                if(!$newArr){
                    $num = count($unit);
                    $unit[$num]["unit"] = $goodsQty[$i]["unit"];
                    $unit[$num]["qty"] = $goodsQty[$i]["qty"];
                    $unit[$num]["goodsUnitId"] = $goodsQty[$i]["goodsUnitId"];
                }
            }
        }
        if(count($unit)){
            for($k = 0;$k < count($unit);$k++){
                $totalQty = $unit[$k]["qty"].$unit[$k]["unit"].$totalQty;
            }
        }else{
            $unitInfo = DB::table("biz_goods_unit_info")
                          ->where("goods",$goodsId)
                          ->where("unit_sort",1)
                          ->pluck("unit");
            $totalQty = "0".$unitInfo;
        }
        return $totalQty;
    }
    
    //新增
    public function addWarehouse($arr){
        $rt = DB::table("base_warehouse")->insert($arr);
        return $rt;
    }
}