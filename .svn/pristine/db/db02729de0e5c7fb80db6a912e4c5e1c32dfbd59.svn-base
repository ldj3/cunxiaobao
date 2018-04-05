<?php namespace App\Models;
use DB;
use Config;
use App\Models\EntryOrder;
use App\Models\EntryGoods;
use App\Models\EntryCustomer;
use Illuminate\Database\Eloquent\Model;
class EntryOrder extends Model {
    protected $table = 'biz_order';
    public $timestamps = false;
    protected $kw = "";
    public function orderList($select="",$where,$offset=0,$rows=0){
        $keyword = isset($where["keyword"])?$where["keyword"]:"";
        $startDate = isset($where["startDate"])?$where["startDate"]:"";
        $endDate = isset($where["endDate"])?$where["endDate"]:"";
        $isReturn = isset($where["isReturn"])?$where["isReturn"]:0;
        $enterpriseId = isset($where["enterpriseId"])?$where["enterpriseId"]:0;
        $shopId = isset($where["shopId"])?$where["shopId"]:0;
		$status = isset($where["status"])?$where["status"]:0;
        $startTime = $startDate?strtotime($startDate." 00:00:00")*1000:"";
        $endTime = $endDate?strtotime($endDate." 23:59:59")*1000:"";
        $goodsId = isset($where["goodsId"])?$where["goodsId"]:null;
        $dimensionalId = isset($where["dimensionalId"])?$where["dimensionalId"]:null;
        $customerId = isset($where["customerId"])?$where["customerId"]:null;
        $order = array();
        if(empty($select))
        {
            $select = array("o.id as orderId","o.order_no as orderNo","o.order_date as orderDate","o.modify_date as modifyDate","o.create_date as createDate","o.total_cost as totalCost","o.total_qty as totalQty","o.total_money as totalMoney","o.receivable_money as receivableMoney","o.receipt_money as receiptMoney","o.editor","o.creator","o.status","o.is_return as isReturn","o.enterprise as enterpriseId","o.shop as shopId","o.operator_name as operatorName","o.operator","o.customer as customerId","c.name as customerName","e.name as enterpriseName");
        }
        $objorder = new EntryOrder;
        $query = DB::table("biz_order as o")
                    ->Join("base_enterprise as e",'o.enterprise', '=','e.id')
                    ->leftJoin("base_customer as c",'o.customer', '=','c.id');
        if($goodsId){
            $query->leftJoin("biz_order_dtl as bod",'o.id', '=','bod.order')
                  ->where("bod.goods",$goodsId);
            if($dimensionalId){
                $query->where("bod.dimensional_id",$dimensionalId);
            }
        }
        if($customerId){
            $query->where("o.customer",$customerId);
        }
        $query->select($select)
              ->where("o.is_return",$isReturn)
              ->where("o.is_deleted",0);
        if($enterpriseId) $query->where("o.enterprise",$enterpriseId);
        if($shopId) $query->where("o.shop",$shopId);
        if($startTime) $query->where("o.order_date",">=",$startTime);
        if($endTime) $query->where("o.order_date","<=",$endTime);
		if($status) $query->where("o.status",$status);
        if($keyword){
            $this->kw = $keyword;
            $query->where(function($query){
                $query->where("o.order_no","like","%".$this->kw."%")
                      ->orWhere("o.id","like","%".$this->kw."%")
                      ->orWhere("c.name","like","%".$this->kw."%");
            });
        }
        $total = $query->count();
        $query->orderBy("o.order_date","desc");
        if($rows){
            $order = $query->skip($offset)->take($rows)->get();
        }else{
            $order = $query->get();
        }
        
        $order = obj2arr($order);

        for($i = 0;$i < count($order);$i++){
            //计算成本
            $order[$i]["totalLr"] = 0;
            $order[$i]["totalCost"] = 0;
            $arr_dtl = DB::table("biz_order_dtl")
                         ->select("cost_price as costPrice", "sal_qty as salQty")
                         ->where("order",$order[$i]["orderId"])
                         ->get();
            $arr_dtl = obj2arr($arr_dtl);
            for($j = 0;$j < count($arr_dtl);$j++){
                $order[$i]["totalCost"] += $arr_dtl[$j]["costPrice"]*$arr_dtl[$j]["salQty"];
            }

            $order[$i]["benefitMoney"] = 0;//订单优惠
            if($order[$i]["totalMoney"] > $order[$i]["receivableMoney"]){
                $order[$i]["benefitMoney"] = $order[$i]["totalMoney"]-$order[$i]["receivableMoney"];
            }
            $order[$i]["benefitMoney"] = doubleval($order[$i]["benefitMoney"]);
            $order[$i]["receiptMoney"] = doubleval($order[$i]["receiptMoney"]);
            $order[$i]["totalMoney"] = doubleval($order[$i]["totalMoney"]);
            $order[$i]["receivableMoney"] = doubleval($order[$i]["receivableMoney"]);
            $order[$i]["totalLr"] = doubleval(($order[$i]["receivableMoney"]-$order[$i]["totalCost"]));
            $order[$i]["totalQk"] = doubleval(($order[$i]["receivableMoney"]-$order[$i]["receiptMoney"]));
            $order[$i]["totalQty"] = doubleval($order[$i]["totalQty"]);
            if(!$order[$i]["customerName"]) $order[$i]["customerName"] = "散客";
            $order[$i]["orderDate"] = date("Y-m-d H:i:s",$order[$i]["orderDate"]/1000);
            $order[$i]["statusMsg"] = $order[$i]["status"];
        }
        $rtn = array("order"=>$order,"total" => $total);
        return $rtn;
    }
    
    //单位时间内总金额
    function sumTotalMoney($arr){
		$query = DB::table("biz_order")
                   ->where("enterprise",$arr["enterpriseId"])
                   ->where("shop",$arr["shopId"])
                   ->where("is_deleted",0)
                   ->where("is_return",$arr["isReturn"]);
        if(!empty($where["status"])){
            $query->where("status","<>","unfinished");
        }
        $sumTotalMoney = $query->where("order_date",">=",$arr["startDate"])
                               ->where("order_date","<=",$arr["endDate"])
                               ->sum("receivable_money");
        $sumTotalMoney = doubleval($sumTotalMoney);
        return $sumTotalMoney;
    }
    
    //单位时间内总成交金额
    function sumMoney($arr){
		$query = DB::table("biz_order")
                   ->where("enterprise",$arr["enterpriseId"])
                   ->where("shop",$arr["shopId"])
                   ->where("is_deleted",0)
                   ->where("is_return",$arr["isReturn"]);
        if(!empty($where["status"])){
            $query->where("status","<>","unfinished");
        }
        $sumMoney = $query->where("order_date",">=",$arr["startDate"])
                          ->where("order_date","<=",$arr["endDate"])
                          ->sum("receivable_money");
        $sumMoney = doubleval($sumMoney);
        return $sumMoney;
    }
    
    //单位时间内总成本
    function sumCostMoney($arr){
		$query = DB::table("biz_order")
                   ->where("enterprise",$arr["enterpriseId"])
                   ->where("shop",$arr["shopId"])
                   ->where("is_deleted",0)
                   ->where("is_return",$arr["isReturn"]);
        if(!empty($where["status"])){
            $query->where("status","<>","unfinished");
        }
        $sumCostMoney = $query->where("order_date",">=",$arr["startDate"])
                              ->where("order_date","<=",$arr["endDate"])
                              ->sum("total_cost");
        $sumCostMoney = doubleval($sumCostMoney);
        return $sumCostMoney;
    }
    
    //单位时间内总实收
    function sumReceiptMoney($arr){
		$query = DB::table("biz_order")
                   ->where("enterprise",$arr["enterpriseId"])
                   ->where("shop",$arr["shopId"])
                   ->where("is_deleted",0)
                   ->where("is_return",$arr["isReturn"]);
        if(!empty($where["status"])){
            $query->where("status","<>","unfinished");
        }
        $sumReceiptMoney = $query->where("order_date",">=",$arr["startDate"])
                                 ->where("order_date","<=",$arr["endDate"])
                                 ->sum("receipt_money");
        $sumReceiptMoney = doubleval($sumReceiptMoney);
        return $sumReceiptMoney;
    }
    
    //单位时间内总订单数
    function countOrder($arr){
		$countOrder = DB::table("biz_order")
                        ->where("enterprise",$arr["enterpriseId"])
                        ->where("shop",$arr["shopId"])
                        ->where("is_deleted",0)
                        ->where("is_return",$arr["isReturn"])
                        ->where("order_date",">=",$arr["startDate"])
                        ->where("order_date","<=",$arr["endDate"])
                        ->count();
        return $countOrder;
    }
    
    //单位时间内商品销售总类数量
    function countOrderGoods($arr){
        $countOrderGoods = DB::table("biz_order_dtl as bod")
                             ->select(DB::raw('count(distinct(bod.goods)) as num'))
                             ->join("biz_order as bo","bod.order","=","bo.id")
                             ->where("bo.enterprise",$arr["enterpriseId"])
                             ->where("bo.shop",$arr["shopId"])
                             ->where("bo.is_deleted",0)
                             ->where("bo.is_return",$arr["isReturn"])
                             ->where("bo.order_date",">=",$arr["startDate"])
                             ->where("bo.order_date","<=",$arr["endDate"])
                             ->first();
        return $countOrderGoods->num;
    }
	
	//单位时间内订单商品总入库
	function sumOrderInWarehouse($arr){
        $sumOrderInWarehouse = DB::table("biz_order_dtl as bod")
								 ->join("biz_order as bo","bod.order","=","bo.id")
								 ->where("bod.enterprise",$arr["enterpriseId"])
								 ->where("bod.shop",$arr["shopId"])
                                 ->where("bo.is_deleted",0)
								 ->where("bo.order_date",">=",$arr["startDate"])
								 ->where("bo.order_date","<=",$arr["endDate"])
								 ->whereIn("bo.is_return",[1,3])
		  						 ->sum("bod.sal_qty");
		$sumOrderInWarehouse = doubleval($sumOrderInWarehouse);
        return $sumOrderInWarehouse;
	}
	
	//单位时间内总利润
	function sumLr($arr){
		$sql = "
                select  sum(bo.receivable_money-bodc.costPrice) as sumLr
                from biz_order as bo
                join (
                            select sum(IFNULL(bod.cost_price,0)*IFNULL(bod.sal_qty,0)) as costPrice,bod.order
                                from biz_order_dtl as bod
                                where bod.enterprise = ?
                                    and bod.shop = ?
                                GROUP BY bod.order
                        )as bodc on bo.id = bodc.order
                where bo.enterprise = ?
                    and bo.shop = ?
                    and bo.is_deleted = 0
                    and bo.is_return = ?
                    and bo.order_date >= ?
                    and bo.order_date <= ?
                    and bo.receivable_money > bodc.costPrice
                ";
		$data = array();
        $data[] = $arr["enterpriseId"];
        $data[] = $arr["shopId"];
        $data[] = $arr["enterpriseId"];
        $data[] = $arr["shopId"];
        $data[] = $arr["isReturn"];
        $data[] = $arr["startDate"];
        $data[] = $arr["endDate"];
        $rt = DB::select($sql,$data);
        $sumLr = doubleval($rt[0]->sumLr);
        return $sumLr;
	}
	
	//单位时间内总其他费用
	function sumOtherMoney($arr){
		$sumOtherMoney = 0;
		if(!empty($arr)){
			$data = DB::table("biz_order")
					->select(DB::raw('sum(receivable_money - total_money) as sumOtherMoney'))
					->where("enterprise",$arr["enterpriseId"])
					->where("shop",$arr["shopId"])
					->where("is_deleted",0)
					->where("order_date",">=",$arr["startDate"])
					->where("order_date","<=",$arr["endDate"])
					->where("is_return",$arr["isReturn"])
					->whereRaw("receivable_money - total_money > 0")
					->first();
			$data = obj2arr($data);
			$sumOtherMoney = doubleval($data["sumOtherMoney"]);
			return $sumOtherMoney;
		}else{
			return $sumOtherMoney;
		}
	}
	
	//单位时间内订单商品总出库
	function sumOrderOutWarehouse($arr){
        $sumOrderOutWarehouse = DB::table("biz_order_dtl as bod")
                                  ->join("biz_order as bo","bod.order","=","bo.id")
								  ->where("bod.enterprise",$arr["enterpriseId"])
								  ->where("bod.shop",$arr["shopId"])
                                  ->where("bo.is_deleted",0)
								  ->where("bo.order_date",">=",$arr["startDate"])
								  ->where("bo.order_date","<=",$arr["endDate"])
								  ->whereIn("bo.is_return",[0,2])
								  ->sum("bod.sal_qty");
        $sumOrderOutWarehouse = doubleval($sumOrderOutWarehouse);
        return $sumOrderOutWarehouse;
	}
	
    //单位时间内订单出入库列表----出入库日流水[PC]
	function salesGoodsList($arr){
        $rt = array();
        if(!empty($arr)){
            $query = DB::table("biz_order_dtl as bod")
                       ->select("bo.order_date as orderDate","bg.name as goodsName","bgd.property_value1 as propertyValue1","bgd.property_value2 as propertyValue2","bo.is_return as isReturn","bo.total_qty as totalQty")
                       ->join("biz_order as bo","bod.order","=","bo.id")
                       ->join("base_goods as bg","bod.goods","=","bg.id")
                       ->leftJoin("base_goods_dimensional as bgd","bod.dimensional_id","=","bgd.id")
                       ->where("bo.enterprise",$arr["enterpriseId"])
                       ->where("bo.shop",$arr["shopId"])
                       ->where("bo.is_deleted",0)
                       ->where("bo.order_date",">=",$arr["startDate"])
                       ->where("bo.order_date","<=",$arr["endDate"])
                       ->orderBy("bo.order_date");
            $total = $query->count();

            $goodsList = $query->skip($arr["offset"])
                               ->take($arr["rows"])
						       ->get();
            $goodsList = obj2arr($goodsList);
            if(empty($goodsList)){
                return $rt;
            }else{
                $this->order_type = Config::get("app.order_type");
                for($i = 0;$i < count($goodsList);$i++){
                    $goodsList[$i]["salesType"] = $this->order_type[$goodsList[$i]["isReturn"]];
                    $goodsList[$i]["totalQty"] = doubleval($goodsList[$i]["totalQty"]);
                    $goodsList[$i]["orderDate"] = date("Y-m-d H:i:s",$goodsList[$i]["orderDate"]/1000);
                    if($goodsList[$i]["isReturn"] == 0 || $goodsList[$i]["isReturn"] == 3){
                        $goodsList[$i]["inOutType"] = "出库";
                    }else{
                        $goodsList[$i]["inOutType"] = "入库";
                    }
                    if(!$goodsList[$i]["propertyValue1"]){
                        $goodsList[$i]["propertyValue1"] = "";
                    }
                    if(!$goodsList[$i]["propertyValue2"]){
                        $goodsList[$i]["propertyValue2"] = "";
                    }
                }
                $rt["data"] = $goodsList;
                $rt["total"] = $total;
                return $rt;
            }
        }else{
            return $rt;
        }
    }
    
	//单位时间内订单出入库列表----出入库日流水
	function orderGoodsList($arr){
        $sql = "
                select `goodsId`, `goodsName`,`dimensionalId` from
                    (
                    select `bod`.`goods` as `goodsId`, `bg`.`name` as `goodsName`, ifnull(`bod`.`dimensional_id`,'') as `dimensionalId`
                        from `biz_order_dtl` as `bod` 
                        inner join `biz_order` as `bo` on `bod`.`order` = `bo`.`id` 
                        inner join `base_goods` as `bg` on `bod`.`goods` = `bg`.`id` 
                        where `bo`.`enterprise` = '".$arr["enterpriseId"]."'
                            and `bo`.`shop` = '".$arr["shopId"]."'
                            and `bo`.`is_deleted` = '0'
                            and `bo`.`order_date` >= '".$arr["startDate"]."'
                            and `bo`.`order_date` <= '".$arr["endDate"]."'
                        group by `bod`.`goods`, `bod`.`dimensional_id` 
                    ) as tmp
                    group by `goodsId`, `dimensionalId`
                    limit ".$arr["rows"]." offset ".$arr["offset"]."
                ";
        $goodsList = DB::select($sql, []);
        $goodsList = obj2arr($goodsList);
		if(!empty($goodsList)){
			$this->order_type = Config::get("app.order_type");
			for($i = 0;$i < count($goodsList);$i++){
				$query = DB::table("biz_order_dtl as bod")
						   ->select("bo.order_date as createDate","bo.operator_name as creator","bo.is_return as inOutType","bod.sal_qty as qty","bod.sal_unit as unit")
						   ->join("biz_order as bo","bod.order","=","bo.id")
						   ->where("bod.goods",$goodsList[$i]["goodsId"])
                           ->where("bo.is_deleted",0)
                           ->where("bo.order_date",">=",$arr["startDate"])
                           ->where("bo.order_date","<=",$arr["endDate"]);
				if(!empty($goodsList[$i]["dimensionalId"])){
					$query->where("bod.dimensional_id",$goodsList[$i]["dimensionalId"]);
					$dim = DB::table("base_goods_dimensional")
							 ->select("property_value1 as propertyValue1","property_value2 as propertyValue2")
							 ->where("id",$goodsList[$i]["dimensionalId"])
							 ->first();
					$goodsList[$i]["propertyValue1"] = $dim->propertyValue1;
					$goodsList[$i]["propertyValue2"] = $dim->propertyValue2;
				}else{
                    $goodsList[$i]["propertyValue1"] = "";
                    $goodsList[$i]["propertyValue2"] = "";
                }
				$goodsList[$i]["inOutList"] = $query->get();
				$goodsList[$i]["inOutList"] = obj2arr($goodsList[$i]["inOutList"]);
				$goodsList[$i]["inNum"] = 0;
                $goodsList[$i]["outNum"] = 0;
				for($j = 0;$j < count($goodsList[$i]["inOutList"]);$j++){
                    if($goodsList[$i]["inOutList"][$j]["inOutType"] == 0 || $goodsList[$i]["inOutList"][$j]["inOutType"] == 3 || $goodsList[$i]["inOutList"][$j]["inOutType"] == 5){
                        $goodsList[$i]["outNum"] += doubleval($goodsList[$i]["inOutList"][$j]["qty"]);
                    }
                    if($goodsList[$i]["inOutList"][$j]["inOutType"] == 1 || $goodsList[$i]["inOutList"][$j]["inOutType"] == 2 || $goodsList[$i]["inOutList"][$j]["inOutType"] == 4){
                        $goodsList[$i]["inNum"] += doubleval($goodsList[$i]["inOutList"][$j]["qty"]);
                    }
					$goodsList[$i]["inOutList"][$j]["qty"] = doubleval($goodsList[$i]["inOutList"][$j]["qty"]);
					$goodsList[$i]["inOutList"][$j]["inOutType"] = $this->order_type[$goodsList[$i]["inOutList"][$j]["inOutType"]];		
				}
			}
		}
		return $goodsList;
	}
	
    //订单详情
    function orderDetail($orderId){
        $customer = new EntryCustomer;
        $goods = new EntryGoods;
        
        $arrOrder = DB::table("biz_order as bo")
                      ->leftJoin("biz_order_logistics as bol","bo.id","=","bol.order_id")
                      ->select("bo.id as orderId","bo.order_no as orderNo","bo.remark","bo.customer","bo.enterprise as enterpriseId","bo.order_date as orderDate","bo.total_qty as totalQty","bo.total_money as totalMoney","bo.receivable_money as receivableMoney","bo.receipt_money as receiptMoney","bo.operator","bo.operator_name as operatorName","bo.creator","bo.editor","bo.is_return as isReturn","bo.status","bo.shop as shopId","bol.id as logisticsId","bol.express_order as expressOrderNum","bol.express_name as expressName")
                      ->where("bo.id",$orderId)
                      ->first();
        if(!$arrOrder){
            return false;
        }
        $arrOrder = obj2arr($arrOrder);
        $arrOrder["orderDate"] = date("Y-m-d H:i:s",$arrOrder["orderDate"]/1000);
        $arrOrder["totalQty"] = doubleval($arrOrder["totalQty"]);
        $arrOrder["totalMoney"] = doubleval($arrOrder["totalMoney"]);
        $arrOrder["receivableMoney"] = doubleval($arrOrder["receivableMoney"]);
        $arrOrder["receiptMoney"] = doubleval($arrOrder["receiptMoney"]);
        $this->order_status = Config::get("app.order_status");
        $arrOrder["status"] = $this->order_status[$arrOrder["status"]];
        $arrOrder["customerName"] = "散客";
        $arrOrder["customerMobile"] = "";
        if($arrOrder["customer"]){
            $objCustomer = $customer->find($arrOrder["customer"],array("name","mobile"));
            $arrOrder["customerName"] = $objCustomer?$objCustomer->name:$arrOrder["customerName"];
            $arrOrder["customerMobile"] = $objCustomer?$objCustomer->mobile:$arrOrder["customerMobile"];
        }
        $totalCost = 0;
        $detail = DB::table("biz_order_dtl")
                    ->select("id","goods as goodsId","sal_qty as salQty","sal_price as salPrice","money","sal_unit as salUnit","dimensional_id as dimensionalId","warehouse_id as warehouseId","cost_price as costPrice","low_qty as lowQty","curr_qty as currQty")
                    ->where("order",$orderId)
                    ->get();
        $detail = obj2arr($detail);
        for($i = 0;$i < count($detail);$i++){
            $detail[$i]["salQty"] = doubleval($detail[$i]["salQty"]);
            $detail[$i]["salPrice"] = doubleval($detail[$i]["salPrice"]);
            $detail[$i]["money"] = doubleval($detail[$i]["money"]);
            $detail[$i]["costPrice"] = doubleval($detail[$i]["costPrice"]);
            $detail[$i]["lowQty"] = doubleval($detail[$i]["lowQty"]);
            $detail[$i]["currQty"] = doubleval($detail[$i]["currQty"]);
            
            $goodsInfo = $goods->find($detail[$i]["goodsId"],array("name as goodsName","image","description","property1","property2","property3","property_title1 as propertyTitle1","property_title2 as propertyTitle2"));
            $detail[$i]["goodsName"] = $goodsInfo->goodsName;
            $detail[$i]["property1"] = $goodsInfo->property1;
            $detail[$i]["property2"] = $goodsInfo->property2;
            $detail[$i]["property3"] = $goodsInfo->property3;
            $detail[$i]["propertyTitle1"] = $goodsInfo->propertyTitle1;
            $detail[$i]["propertyTitle2"] = $goodsInfo->propertyTitle2;
            $detail[$i]["costMoney"] = doubleval($detail[$i]["costPrice"]*$detail[$i]["salQty"]);
            $totalCost += $detail[$i]["costMoney"];
            $detail[$i]["image"] = $goodsInfo->image;
            
            if($detail[$i]["dimensionalId"]){
                $arrDim = DB::table("base_goods_dimensional")
                            ->select(array("property_value1 as propertyValue1","property_value2 as propertyValue2","image","qty"))
                            ->where("id",$detail[$i]["dimensionalId"])
                            ->first();
                if($arrDim){
                    $detail[$i]["propertyValue1"] = $arrDim->propertyValue1;
                    $detail[$i]["propertyValue2"] = $arrDim->propertyValue2;
                    $detail[$i]["image"] = $arrDim->image;
                }
            }
            if(!$detail[$i]["image"]){
                $detail[$i]["image"] = Config::get("app.url").Config::get("app.goods_default_pic");
            }
        }
        $arrOrder["totalCost"] = $totalCost;
        $return = array();
        $return["orderData"] = $arrOrder;
        $return["detail"] = $detail;
        return $return;
    }
    
    //获取商品分析列表（商品去重列表）
    public function goodsAnalysisList($arr){
        $sql = "select sum(sumQty) as sumQty,goodsId,dimensionalId,goodsName,propertyValue1,propertyValue2 from ( 
                select sum(bod.sal_qty) as sumQty, `bod`.`goods` as `goodsId`,ifnull(`bod`.`dimensional_id`,'') as `dimensionalId`, `bg`.`name` as `goodsName`, `bgd`.`property_value1` as `propertyValue1`, `bgd`.`property_value2` as `propertyValue2` 
                    from `biz_order_dtl` as `bod` inner join `biz_order` as `bo` on `bod`.`order` = `bo`.`id` 
                    inner join `base_goods` as `bg` on `bod`.`goods` = `bg`.`id` 
                    left join `base_goods_dimensional` as `bgd` on `bod`.`dimensional_id` = `bgd`.`id` 
                    where `bo`.`enterprise` = '".$arr["enterpriseId"]."'
                        and `bo`.`shop` = '".$arr["shopId"]."'
                        and `bo`.`is_deleted` = '0'
                        and `bo`.`is_return` = '".$arr["isReturn"]."' ";
        if(!empty($arr["warehouseId"])){
            $sql .= "and `bod`.`warehouse_id` = '".$arr["warehouseId"]."'";
        }
        if(!empty($arr["startDate"])){
            $sql .= "and `bo`.`order_date` >= '".$arr["startDate"]."'";
            $sql .= "and `bo`.`order_date` <= '".$arr["endDate"]."'";
        }
        $sql .= "group by `bod`.`goods`, `bod`.`dimensional_id` 
                    ) as tmp
                group by `goodsId`, `dimensionalId` 
                order by `sumQty` desc
                limit ".$arr["rows"]." offset ".$arr["offset"];
        $goodsAnalysisList =  DB::select($sql,[]);
        return $goodsAnalysisList;
    }
    
    //客户销售分析----根据订单获取客户列表
    function customerAnalysisList($arr){
        $customerList = DB::table("biz_order as bo")
                          ->join("base_customer as bc","bo.customer","=","bc.id")
                          ->select(DB::raw('SUM(bo.receivable_money) as sumMoney'),"bc.id as customerId","bc.name as customerName")
                          ->where("bo.enterprise",$arr["enterpriseId"])
                          ->where("bo.is_deleted",0)
                          ->where("bo.is_return",$arr["isReturn"])
                          ->whereNotNull("bo.customer")
                          ->where("bo.order_date",">=",$arr["startDate"])
                          ->where("bo.order_date","<=",$arr["endDate"])
                          ->groupBy("bo.customer")
                          ->orderBy("sumMoney","desc")
                          ->skip($arr["offset"])
                          ->take($arr["rows"])
                          ->get();
        return $customerList;
    }
    
    //根据客户ID获取订单列表
    function customerBillList($arr){
        $customerList = DB::table('biz_order')
                          ->select("id as orderId","order_date as orderDate","is_return as isReturn","settle_type as settleType","order_no as orderNo","receivable_money as receivableMoney","receipt_money as receiptMoney")
                          ->where('is_deleted',0)
                          ->where('enterprise',$arr["enterpriseId"])
                          ->where('customer',$arr["customerId"])
                          ->where('status','<>','unfinished')
                          ->where("order_date", ">=",$arr["startDate"])
                          ->where("order_date", "<=",$arr["endDate"])
                          ->orderBy("order_date", "desc")
                          ->skip($arr["offset"])
                          ->take($arr["rows"])
                          ->get();
        return $customerList;
    }

    //保存订单信息
    public static function saveOrderInfo($id,$order_no,$customer,$enterpriseId,$shopId,$createName,$short_code,$is_return,$status,$total_qty,$total_money,$total_cost,$receivable_money,$receipt_money,$settle_type,$operator,$operator_name,$remark){
        
        $time = get_ms();
        $maxVersion = max_version('biz_order',$enterpriseId) + 1;

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
          'customer'=>$customer, //客户ID
          'order_no'=>$order_no,
          'order_date'=>$time,
          'short_code'=>$short_code,
          'is_return'=>$is_return,
          'status'=>$status,
          'total_qty'=>$total_qty,
          'total_money'=>$total_money,
          'total_cost'=>$total_cost,
          'receivable_money'=>$receivable_money,
          'receipt_money'=>$receipt_money,
          'print_times'=>'0',
          'settle_type'=>$settle_type,
          'operator'=>$operator,
          'operator_name'=>$operator_name,
          'remark'=>$remark
          );
        //print_r($data);
        DB::table("biz_order")->insert($data);
        return $id;
    }
}