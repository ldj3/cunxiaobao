<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
use App\Models\EntryUnit;
use App\Models\EntryWarehouse;
use App\Models\EntryGoodsUnitPrice;

class EntryGoods extends Model {
    protected $table = 'base_goods';
    public $timestamps = false;
    protected $kw = "";
    protected $barcode = "";
    
    //商品列表
    public function goodsList($enterpriseId,$keyword,$barcode,$warehouseId,$page,$rows){
        $offset = ($page-1)*$rows;
        $query = DB::table("base_goods as bg")
                   ->select("bg.id","bg.name","bg.is_bivariate as isBivariate","bg.property1","bg.property2","bg.property3","bg.image","bg.remark");
        if($barcode){
            $this->barcode = $barcode;
            $query->join("biz_goods_unit_info as bgui",'bg.id', '=','bgui.goods')
                  ->where(function($sql1)
                    {
                        $sql1->where("bgui.barcode",$this->barcode)
                             ->orWhere("bgui.barcode",$this->barcode);
                    })
                  ->where("bg.is_bivariate",0);
        }
        $query->where("bg.enterprise",$enterpriseId)
              ->where("bg.is_deleted",0);

        if($keyword){
            $this->kw = changeStr($keyword);
            $query->where(function($sql)
                    {
                        $sql->where("bg.name","like",$this->kw)
                            ->orWhere("bg.property1","like",$this->kw)
                            ->orWhere("bg.property2","like",$this->kw)                                
                            ->orWhere("bg.property3","like",$this->kw)
                            ->orWhere("bg.code","like",$this->kw);
                    });
        }
        $total = $query->count();
        $goods = $query->orderBy("bg.create_date","desc")
                       ->skip($offset)
                       ->take($rows)
                       ->get();
        $goods = obj2arr($goods);
        
        for($i = 0;$i < count($goods); $i++){
            if(!$goods[$i]["image"]){
                $goods[$i]["image"] = Config::get("app.url").Config::get("app.goods_default_pic");
            }
            if($goods[$i]["isBivariate"]){
                $goods[$i]["totalQty"] = "";
            }else{
                $goods[$i]["totalQty"] = EntryWarehouse::findQtyByGoodsId($goods[$i]["id"],$warehouseId);
                $goods[$i]["unit"] =  EntryUnit::barcodeListByGoodsId($goods[$i]["id"]);
            }
            
            if(empty($goods[$i]["unit"])){
                $goods[$i]["unit"] = array();
            }
        }

        $data["goods"] = $goods;
        $data["total"] = $total;
        return $data;
    }
    
    //商品修改
    static function updateGoods($arr){
        $arr["modify_date"] = get_ms();
        $arr["version"] = max_version("base_goods",$arr["enterprise"])+1;
        $rt = DB::table("base_goods")
                ->where("id",$arr["id"])
                ->update($arr);
        return $rt;
    }
    
    //二维商品修改
    static function updateDimensional($arr){
        $rt = DB::table("base_goods_dimensional")
                ->where("id",$arr["id"])
                ->update($arr);
        return $rt;
    }
    
    //二维商品新增
	public function addGoodsDimensional($enterpriseId,$shopId,$createName,$goodsDimensionalMaxVersion = 0,$goods,$propertyValue1,$propertyValue2,$image,$remark){
        if($goodsDimensionalMaxVersion == 0){
            $goodsDimensionalMaxVersion = max_version('base_goods_dimensional',$enterpriseId)+1;
        }
        $id = get_newid("base_goods_dimensional");
        $time = get_ms();
        $data = array(
            'id'=>$id,
            'goods'=>$goods,
            'property_value1'=>$propertyValue1,
            'property_value2'=>$propertyValue2,
            'image'=>$image,
            'remark'=>$remark,
            
            'is_deleted'=>0,
            'create_date'=>$time,
            'modify_date'=>$time,
            'creator'=>$createName,
            'editor'=>$createName,
            'version'=>$goodsDimensionalMaxVersion,
            'enterprise'=>$enterpriseId,
            'shop'=>$shopId
        );

        DB::table('base_goods_dimensional')->insert($data);
        return $id;
    }
    
    //新增商品单位
    static function addGoodsUnit($arr,$data,$enterpriseId,$dimensionalId){
        for($i = 0;$i < count($data);$i++){
            $arr["id"] = get_newid("biz_goods_unit_info");
            $arr["version"] = max_version("biz_goods_unit_info",$enterpriseId)+1;//版本
            $arr["goods_unit_id"] = trim($data[$i]["goodsUnitId"]);
            $arr["unit"] = trim($data[$i]["unitName"]);
            $arr["unit_sort"] = trim($data[$i]["unitSort"]);
            $arr["retail_price"] = trim($data[$i]["retailPrice"]);
            $arr["retail_price1"] = trim($data[$i]["retailPrice1"]);
            $arr["trade_price"] = trim($data[$i]["tradePrice"]);
            $arr["cost_price"] = trim($data[$i]["costPrice"]);
            $arr["barcode"] = trim($data[$i]["barcode"]);
            $arr["barcode1"] = trim($data[$i]["barcode1"]);
            if($dimensionalId){
                $arr["dimensional_id"] = $dimensionalId;
            }
            DB::table("biz_goods_unit_info")->insert($arr);
        }
        return true;
    }
    
    //修改商品单位
    static function updateGoodsUnit($arr,$data,$enterpriseId){
        for($i = 0;$i < count($data);$i++){
            $arr["version"] = max_version("biz_goods_unit_info",$enterpriseId)+1;//版本
            $arr["goods_unit_id"] = trim($data[$i]["goodsUnitId"]);
            $arr["unit"] = trim($data[$i]["unitName"]);
            $arr["unit_sort"] = trim($data[$i]["unitSort"]);
            $arr["retail_price"] = trim($data[$i]["retailPrice"]);
            $arr["retail_price1"] = trim($data[$i]["retailPrice1"]);
            $arr["trade_price"] = trim($data[$i]["tradePrice"]);
            $arr["cost_price"] = trim($data[$i]["costPrice"]);
            $arr["barcode"] = trim($data[$i]["barcode"]);
            $arr["barcode1"] = trim($data[$i]["barcode1"]);
                
            DB::table("biz_goods_unit_info")
              ->where("id",$data[$i]["id"])
              ->update($arr);
        }
        return true;
    }
    
    //商品详情--一维
    static function findGoods($goodsId,$shopId,$warehouseId){
        $goods = DB::table("base_goods")
                   ->select("id","name","property1","property2","property3","image","remark","property_title1 as propertyTitle1","property_title2 as propertyTitle2","goods_unit_group as goodsUnitGroup","is_bivariate as isBivariate")
                   ->where("id",$goodsId)
                   ->first();
        $goods = obj2arr($goods);
        if(!empty($goods)){
            $goods["image"] = $goods["image"]?$goods["image"]:(Config::get("app.url").Config::get("app.goods_default_pic"));
            //单位
            $unitList = DB::table("biz_goods_unit_info")
                         ->select("id as goodsUnitInfoId","unit","limit_price as limitPrice","retail_price as retailPrice","retail_price1 as retailPrice1","trade_price as tradePrice","cost_price as costPrice","barcode","barcode1")
                         ->where("goods",$goodsId)
                         ->where(function($sql){
                                $sql->where("dimensional_id",null)
                                    ->orWhere("dimensional_id","");
                            })
                         ->orderBy("unit_sort")
                         ->get();
            $unitList = obj2arr($unitList);
            for($a = 0;$a < count($unitList);$a++){
                $unitList[$a]["limitPrice"] = doubleval($unitList[$a]["limitPrice"]);
                $unitList[$a]["retailPrice"] = doubleval($unitList[$a]["retailPrice"]);
                $unitList[$a]["retailPrice1"] = doubleval($unitList[$a]["retailPrice1"]);
                $unitList[$a]["tradePrice"] = doubleval($unitList[$a]["tradePrice"]);
                $unitList[$a]["costPrice"] = doubleval($unitList[$a]["costPrice"]);
                $unitList[$a]["barcode"] = $unitList[$a]["barcode"]?$unitList[$a]["barcode"]:"";
                $unitList[$a]["barcode1"] = $unitList[$a]["barcode1"]?$unitList[$a]["barcode1"]:"";
            }
            $goods["unitList"] = $unitList;
                    
            //总库存
            $goods["totalQty"] = EntryWarehouse::findQtyByGoodsId($goods["id"],$warehouseId);
            
            //库存详情
            $warehouseArr = DB::table("biz_warehouse_relation as bwr")
                              ->select("bwr.qty","bwr.unit","bwr.goods_unit_id as goodsUnitId","bwr.warehouse_id as warehouseId","bw.warehouse_name as warehouseName")
                              ->join("base_warehouse as bw","bwr.warehouse_id","=","bw.id")
                              ->where("bwr.goods",$goodsId)
                              ->where("bwr.shop",$shopId)
                              ->where(function($sql){
                                    $sql->where("bwr.dimensional_goods_id",null)
                                        ->orWhere("bwr.dimensional_goods_id","");
                                })
                              ->whereIn("bwr.warehouse_id",$warehouseId)
                              ->orderBy("bwr.unit_sort")
                              ->orderBy("bwr.warehouse_id","asc")
                              ->get();
            $warehouseArr = obj2arr($warehouseArr);
            
            $warehouse = array();
            if(!empty($warehouseArr)){
                for($i = 0;$i < count($warehouseArr);$i++){
                    $warehouseArr[$i]["qty"] = doubleval($warehouseArr[$i]["qty"]);
                    if($i==0){
                        $warehouse[0]["unit"] = $warehouseArr[$i]["unit"];
                        $warehouse[0]["qty"] = $warehouseArr[$i]["qty"];
                        $warehouse[0]["warehouseId"] = $warehouseArr[$i]["warehouseId"];
                        $warehouse[0]["warehouseName"] = $warehouseArr[$i]["warehouseName"];
                        $warehouse[0]["total"] = $warehouseArr[$i]["qty"].$warehouseArr[$i]["unit"];
                    }else{
                        $newArr = 0;
                        for($j = 0;$j < count($warehouse);$j++){
                            if($warehouseArr[$i]["warehouseId"] == $warehouse[$j]["warehouseId"]){
                                $warehouse[$j]["total"] = $warehouseArr[$i]["qty"].$warehouseArr[$i]["unit"].$warehouse[$j]["total"];
                                $newArr = 1;
                            }
                        }
                        if(!$newArr){
                            $num = count($warehouse);
                            $warehouse[$num]["unit"] = $warehouseArr[$i]["unit"];
                            $warehouse[$num]["qty"] = $warehouseArr[$i]["qty"];
                            $warehouse[$num]["warehouseId"] = $warehouseArr[$i]["warehouseId"];
                            $warehouse[$num]["warehouseName"] = $warehouseArr[$i]["warehouseName"];
                            $warehouse[$num]["total"] = $warehouseArr[$i]["qty"].$warehouseArr[$i]["unit"];
                        }
                    }
                }
            }else{
                $unit = DB::table("biz_goods_unit_info")
                          ->select("unit")
                          ->where("goods",$goodsId)
                          ->orderBy("unit_sort")
                          ->first();
                $warehouseName = DB::table("base_warehouse")
                               ->whereIn("id",$warehouseId)
                               ->lists("warehouse_name");
                for($b = 0;$b < count($warehouseName);$b++){
                    $warehouse[$b]["unit"] = $unit->unit;
                    $warehouse[$b]["qty"] = 0;
                    $warehouse[$b]["warehouseId"] = "";
                    $warehouse[$b]["warehouseName"] = $warehouseName[$b];
                    $warehouse[$b]["total"] = "0".$unit->unit;
                }
            }
            $goods["warehouse"] = $warehouse;
        }
        
        return $goods;
    }
    
    //商品详情--二维
    static function findDimensional($goodsId,$dimensionalId,$shopId,$warehouseId){
		$goods = DB::table("base_goods_dimensional as bgd")
				   ->join("base_goods as bg","bgd.goods","=","bg.id")
                   ->select("bgd.id","bg.name","bg.property_title1 as propertyTitle1","bg.property_title2 as propertyTitle2","bgd.property_value1 as propertyValue1","bgd.property_value2 as propertyValue2","bgd.image","bgd.remark","bg.goods_unit_group as goodsUnitGroup")
                   ->where("bgd.id",$dimensionalId)
                   ->first();
        $goods = obj2arr($goods);
        if(!empty($goods)){
            //单位
            $entryUnit = new EntryUnit();
            $unitList = $entryUnit->barcodeListByDimensionalId($goodsId,$dimensionalId);
            if(!$unitList){
                $unitList = $entryUnit->barcodeListByGoodsId($goodsId);
            }
            for($a = 0;$a < count($unitList);$a++){
                $unitList[$a]["limitPrice"] = doubleval($unitList[$a]["limitPrice"]);
                $unitList[$a]["retailPrice"] = doubleval($unitList[$a]["retailPrice"]);
                $unitList[$a]["retailPrice1"] = doubleval($unitList[$a]["retailPrice1"]);
                $unitList[$a]["tradePrice"] = doubleval($unitList[$a]["tradePrice"]);
                $unitList[$a]["costPrice"] = doubleval($unitList[$a]["costPrice"]);
            }
            $goods["unitList"] = $unitList;
                    
            //总库存
            $goods["totalQty"] = EntryWarehouse::findQtyByDimensionalId($goodsId,$dimensionalId,$warehouseId);
            
            //库存详情
            $warehouseArr = DB::table("biz_warehouse_relation as bwr")
                              ->select("bwr.qty","bwr.unit","bwr.goods_unit_id as goodsUnitId","bwr.warehouse_id as warehouseId","bw.warehouse_name as warehouseName")
                              ->join("base_warehouse as bw","bwr.warehouse_id","=","bw.id")
                              ->where("bwr.goods",$goodsId)
							  ->where("bwr.dimensional_goods_id",$dimensionalId)
                              ->where("bwr.shop",$shopId)
                              ->whereIn("bwr.warehouse_id",$warehouseId)
                              ->orderBy("bwr.unit_sort")
                              ->orderBy("bwr.warehouse_id","asc")
                              ->get();
            $warehouseArr = obj2arr($warehouseArr);
            
            $warehouse = array();
            if(!empty($warehouseArr)){
                for($i = 0;$i < count($warehouseArr);$i++){
                    $warehouseArr[$i]["qty"] = doubleval($warehouseArr[$i]["qty"]);
                    if($i==0){
                        $warehouse[0]["unit"] = $warehouseArr[$i]["unit"];
                        $warehouse[0]["qty"] = $warehouseArr[$i]["qty"];
                        $warehouse[0]["warehouseId"] = $warehouseArr[$i]["warehouseId"];
                        $warehouse[0]["warehouseName"] = $warehouseArr[$i]["warehouseName"];
                        $warehouse[0]["total"] = $warehouseArr[$i]["qty"].$warehouseArr[$i]["unit"];
                    }else{
                        $newArr = 0;
                        for($j = 0;$j < count($warehouse);$j++){
                            if($warehouseArr[$i]["warehouseId"] == $warehouse[$j]["warehouseId"]){
                                $warehouse[$j]["total"] = $warehouseArr[$i]["qty"].$warehouseArr[$i]["unit"].$warehouse[$j]["total"];
                                $newArr = 1;
                            }
                        }
                        if(!$newArr){
                            $num = count($warehouse);
                            $warehouse[$num]["unit"] = $warehouseArr[$i]["unit"];
                            $warehouse[$num]["qty"] = $warehouseArr[$i]["qty"];
                            $warehouse[$num]["warehouseId"] = $warehouseArr[$i]["warehouseId"];
                            $warehouse[$num]["warehouseName"] = $warehouseArr[$i]["warehouseName"];
                            $warehouse[$num]["total"] = $warehouseArr[$i]["qty"].$warehouseArr[$i]["unit"];
                        }
                    }
                }
            }else{
                $unit = DB::table("biz_goods_unit_info")
                          ->select("unit")
                          ->where("goods",$goodsId)
						  ->where("dimensional_id",$dimensionalId)
                          ->orderBy("unit_sort")
                          ->first();
                if(empty($unit)){
                    $unitName = "";
                }else{
                    $unitName = $unit->unit;
                }
                $warehouseName = DB::table("base_warehouse")
                                   ->whereIn("id",$warehouseId)
                                   ->lists("warehouse_name");
                for($b = 0;$b < count($warehouseName);$b++){
                    $warehouse[$b]["unit"] = $unitName;
                    $warehouse[$b]["qty"] = 0;
                    $warehouse[$b]["warehouseId"] = "";
                    $warehouse[$b]["warehouseName"] = $warehouseName[$b];
                    $warehouse[$b]["total"] = "0".$unitName;
                }
            }
            $goods["warehouse"] = $warehouse;
        }
        
        return $goods;
    }
    
    //删除商品--一维
    public function delGoods($goodsId,$enterpriseId){
        $rt = DB::table("base_goods")
                ->where("id",$goodsId)
                ->update(["is_deleted" => 1,"modify_date" => get_ms(),"version" => max_version("base_goods",$enterpriseId)+1]);
        if(!$rt){
            return $rt;
        }
        $rtn = DB::table("biz_goods_unit_info")
                 ->where("goods",$goodsId)
                 ->update(["is_deleted" => 1,"modify_date" => get_ms(),"version" => max_version("biz_goods_unit_info",$enterpriseId)+1]);
        return $rtn;
    }
    
    //删除二维商品--二维
    static function delDimensional($goods){
        $rt = DB::table("base_goods_dimensional")
                ->where("id",$goods["id"])
                ->update($goods);
        return $rt;
    }
    
    //获取二维商品属性列表
    static function getPropertyLsit($goodsId){
        //第一个属性
        $property[0] = DB::table('base_goods_dimensional')
                             ->where('goods',$goodsId)
                             ->where('is_deleted',0)
                             ->distinct()
                             ->lists('property_value1');
                             
        //第二个属性
        $property[1] = DB::table('base_goods_dimensional')
                             ->where('goods',$goodsId)
                             ->where('is_deleted',0)
                             ->distinct()
                             ->lists('property_value2');
        return $property;
    }
    
    //获取指定goods二维商品列表
    static function dimensionalGoodsList($goodsId,$property1,$property2,$page,$rows){
        $offset = ($page-1)*$rows;
        $query = DB::table("base_goods_dimensional")
                   ->select("id","property_value1 as propertyValue1","property_value2 as propertyValue2","image","remark")
                   ->where("goods",$goodsId)
                   ->where("is_deleted",0);
		if($property1){
			$query->where("property_value1",$property1);
		}
		if($property2){
			$query->where("property_value2",$property2);
		}
        $goods = $query->orderBy("create_date","desc")
                       ->skip($offset)
                       ->take($rows)
                       ->get();
        $goods = obj2arr($goods);
        for($i = 0;$i < count($goods); $i++){
            if(!$goods[$i]["image"]){
                $goods[$i]["image"] = Config::get("app.url").Config::get("app.goods_default_pic");
            }
            $goods[$i]["unit"] =  EntryUnit::barcodeListByDimensionalId($goodsId,$goods[$i]["id"]);
        }
        //$goods = if_null($goods);
        $total = $query->count();
        if(empty($goods)){
            return $goods;
        }else{
            $data["data"] = $goods;
            $data["total"] = $total;
            return $data;
        }
    }
	
	//商品类型总数
	function countGoods($enterpriseId){
		$countGoods = DB::table("base_goods")
						   ->where("enterprise",$enterpriseId)
						   ->count();
		return $countGoods;
	}
    
    //获取二维商品属性列表
    function getDimensionalPropertyList($goodsId){
        $property = array();
        //第一个属性
        $property["property1"] = DB::table('base_goods_dimensional')
                                   ->where('goods',$goodsId)
                                   ->where('is_deleted',0)
                                   ->distinct()
                                   ->lists('property_value1');
                             
        //第二个属性
        $property["property2"] = DB::table('base_goods_dimensional')
                                   ->where('goods',$goodsId)
                                   ->where('is_deleted',0)
                                   ->distinct()
                                   ->lists('property_value2');
        return $property;
    }
    
	//根据二维ID查询主商品ID
	function findGoodsByDimensionalId($dimensionalId){
		$goodsId = DB::table("base_goods_dimensional")
					 ->where("id",$dimensionalId)
					 ->pluck("goods");
		return $goodsId;
	}
    
    public function addGoodsInfo($enterpriseId,$shopId,$createName,$data,$unitMaxVersion,$goodsMaxVersion,$goodsUnitPriceMaxVersion,$warehouseRelationMaxVersion,$inoutDtlMaxVersion,$warehouseList){

        $id = create_uuid();
        $time = get_ms();
        //新建单位
        //echo $data[4].' === '.$enterpriseId;
        $unitName = $data[4];

        $groupId = EntryUnit::addMoreUnitList($enterpriseId,$shopId,$unitMaxVersion,$createName,$unitName);

        $unitList = EntryUnit::getUnitGroupList($groupId);
        $unitInfo = $unitList[0];

        //新建商品
        $name = $data[0];
        $property1 = $data[1];
        $property2 = $data[2];
        $property3 = $data[3];
        $goods_unit_group = $groupId;
        $remark = $data[12];
        //echo " goodsMaxVersion = ".$goodsMaxVersion.'  ';
        $goodsId = EntryGoods::addGoodsData($enterpriseId,$shopId,$createName,$goodsMaxVersion,$name,$property1,$property2,$property3,$goods_unit_group,0,0,0,0,null,null,null,null,$remark);
        //echo " $goodsId = ".$goodsId;
        //新建商品单位价格信息
        $goodsUnitId = $unitInfo->id;
        $goodsUnitSort = $unitInfo->sort;
        $unitName = $unitInfo->name;
        $limit_price = $data[9];
        $retail_price = $data[6];
        $retail_price1 = $data[7];
        $trade_price = $data[5];
        $cost_price = $data[8];
        $barcode = $data[10];
        $barcode1 = $data[11];
        EntryGoodsUnitPrice::addGoodsUnitPrice($enterpriseId,$shopId,$createName,$goodsUnitPriceMaxVersion,$goodsId,null,$goodsUnitId,$unitName,$goodsUnitSort,0,0,$limit_price,$retail_price,$retail_price1,$trade_price,$cost_price,$barcode,$barcode1);
        for ($unitIndex = 1; $unitIndex < count($unitList); $unitIndex++) { 
            # code...
            $goodsUnitId = $unitList[$unitIndex]->id;
            $goodsUnitSort = $unitList[$unitIndex]->sort;
            $unitName = $unitList[$unitIndex]->name;
            $limit_price = '0';
            $retail_price = '0';
            $retail_price1 = '0';
            $trade_price = '0';
            $cost_price = '0';
            $barcode = '';
            $barcode1 = '';
            EntryGoodsUnitPrice::addGoodsUnitPrice($enterpriseId,$shopId,$createName,$goodsUnitPriceMaxVersion,$goodsId,null,$goodsUnitId,$unitName,$goodsUnitSort,0,0,$limit_price,$retail_price,$retail_price1,$trade_price,$cost_price,$barcode,$barcode1);
        }

        //新建商品库存.循环添加库存
        $goodsUnitId = $unitInfo->id;
        $goodsUnitSort = $unitInfo->sort;
        $unitName = $unitInfo->name;
        $index = 13;
        foreach ($warehouseList as $key => $value) {
            # code...
            $qty = $data[$index];
            //echo $value.' '.$qty.' ';
            EntryWarehouseRelation::saveWarehouseRelation($enterpriseId,$shopId,$createName,$warehouseRelationMaxVersion,$inoutDtlMaxVersion,$value,$goodsId,null,$goodsUnitId,$unitName,$goodsUnitSort,$qty,null,null,null,null,'stockBegin');
            $index++;
        }
        return $goodsId;
    }

    public function addGoodsData($enterpriseId,$shopId,$createName,$goodsMaxVersion = 0,$name,$property1,$property2,$property3,$goods_unit_group,$is_batch = 0,$shelf_life = 0,$downlimit_shelf_life = 0,$is_bivariate = 0,
        $category = '',$image = '',$property_title1 = '',$property_title2 = '',$remark=''){

        if($goodsMaxVersion == 0){
            $goodsMaxVersion = max_version('base_goods',$enterpriseId)+1;
        }

        $id = create_uuid();
        $time = get_ms();
        $data = array(
            'id'=>$id,
            'name'=>$name,
            'property1'=>$property1,
            'property2'=>$property2,
            'property3'=>$property3,
            'goods_unit_group'=>$goods_unit_group,
            'is_batch'=>$is_batch,
            'shelf_life'=>$shelf_life,
            'downlimit_shelf_life'=>$downlimit_shelf_life,
            'is_bivariate'=>$is_bivariate,
            'category'=>$category,
            'image'=>$image,
            'property_title1'=>$property_title1,
            'property_title2'=>$property_title2,
            'remark'=>$remark,
            
            'is_deleted'=>0,
            'create_date'=>$time,
            'modify_date'=>$time,
            'creator'=>$createName,
            'editor'=>$createName,
            'version'=>$goodsMaxVersion,
            'enterprise'=>$enterpriseId,
            'shop'=>$shopId
        );

        //print_r($data);
        DB::table('base_goods')->insert($data);
        return $id;
    }

    public function getGoodListBarcode($enterpriseId){
        $sql = "select goods.id as goods_id,goods.is_deleted,goods.name,goods.property1,goods.property2,goods.property3,ginfo.barcode from biz_goods_unit_info ginfo,base_goods goods where ginfo.goods = goods.id and ginfo.enterprise = '".$enterpriseId."' and ginfo.is_deleted = 0 and goods.is_deleted = 0 and goods.is_bivariate = 0";
        $goodList = DB::select($sql,array());

        return $goodList;
        //print_r($goodList);
    }
    
    //新增商品
    public function addGoods($arr){
        $rt = DB::table('base_goods')
                ->insert($arr);
        return $rt;
    }
    
    //商品详情--一维(小程序二维码下单)
    public function findGoodsByGoodsId($goodsId,$shopId){
        $goods = DB::table("base_goods")
                   ->select("id","name","enterprise as enterpriseId","property1","property2","property3","image","remark","property_title1 as propertyTitle1","property_title2 as propertyTitle2","goods_unit_group as goodsUnitGroup")
                   ->where("id",$goodsId)
                   ->first();
        $entryWarehouse = new EntryWarehouse();
        $warehouseList = $entryWarehouse -> warehouseListByShop($goods->enterpriseId,$shopId,null,null);
        $warehouseId = [];
        $warehouseName = [];
        for($m = 0;$m < count($warehouseList);$m++){
            $warehouseId[] = $warehouseList[$m]->warehouseId;
            $warehouseName[] = $warehouseList[$m]->warehouseName;
        }

        if(!empty($goods)){
            $goods->image = $goods->image?$goods->image:(Config::get("app.url").Config::get("app.goods_default_pic"));
            //单位
            $entryUnit = new EntryUnit();
            $unitList = $entryUnit->barcodeListByGoodsId($goodsId);

            for($a = 0;$a < count($unitList);$a++){
                $unitList[$a]->limitPrice = doubleval($unitList[$a]->limitPrice);
                $unitList[$a]->retailPrice = doubleval($unitList[$a]->retailPrice);
                $unitList[$a]->retailPrice1 = doubleval($unitList[$a]->retailPrice1);
                $unitList[$a]->tradePrice = doubleval($unitList[$a]->tradePrice);
                $unitList[$a]->costPrice = doubleval($unitList[$a]->costPrice);
                $unitList[$a]->barcode = $unitList[$a]->barcode?$unitList[$a]->barcode:"";
                $unitList[$a]->barcode1 = $unitList[$a]->barcode1?$unitList[$a]->barcode1:"";
            }
            $goods->unitList = $unitList;
            
            //总库存
            $goods->totalQty = $entryWarehouse -> findQtyByGoodsId($goods->id,$warehouseId);
            
            //库存详情
            $warehouseArr = DB::table("biz_warehouse_relation as bwr")
                              ->select("bwr.qty","bwr.unit","bwr.goods_unit_id as goodsUnitId","bwr.warehouse_id as warehouseId","bw.warehouse_name as warehouseName")
                              ->join("base_warehouse as bw","bwr.warehouse_id","=","bw.id")
                              ->where("bwr.goods",$goodsId)
                              ->where("bwr.shop",$shopId)
                              ->where(function($sql){
                                    $sql->where("bwr.dimensional_goods_id",null)
                                        ->orWhere("bwr.dimensional_goods_id","");
                                })
                              ->whereIn("bwr.warehouse_id",$warehouseId)
                              ->orderBy("bwr.unit_sort")
                              ->orderBy("bwr.warehouse_id","asc")
                              ->get();
            
            $warehouse = array();
            if(!empty($warehouseArr)){
                for($i = 0;$i < count($warehouseArr);$i++){
                    $warehouseArr[$i]->qty = doubleval($warehouseArr[$i]->qty);
                    if($i==0){
                        $warehouse[0]->unit = $warehouseArr[$i]->unit;
                        $warehouse[0]->qty = $warehouseArr[$i]->qty;
                        $warehouse[0]->warehouseId = $warehouseArr[$i]->warehouseId;
                        $warehouse[0]->warehouseName = $warehouseArr[$i]->warehouseName;
                        $warehouse[0]->total = $warehouseArr[$i]->qty.$warehouseArr[$i]->unit;
                    }else{
                        $newArr = 0;
                        for($j = 0;$j < count($warehouse);$j++){
                            if($warehouseArr[$i]->warehouseId == $warehouse[$j]->warehouseId){
                                $warehouse[$j]->total = $warehouseArr[$i]->qty.$warehouseArr[$i]->unit.$warehouse[$j]->total;
                                $newArr = 1;
                            }
                        }
                        if(!$newArr){
                            $num = count($warehouse);
                            $warehouse[$num]->unit = $warehouseArr[$i]->unit;
                            $warehouse[$num]->qty = $warehouseArr[$i]->qty;
                            $warehouse[$num]->warehouseId = $warehouseArr[$i]->warehouseId;
                            $warehouse[$num]->warehouseName = $warehouseArr[$i]->warehouseName;
                            $warehouse[$num]->total = $warehouseArr[$i]->qty.$warehouseArr[$i]->unit;
                        }
                    }
                }
            }else{
                $unit = DB::table("biz_goods_unit_info")
                          ->select("unit")
                          ->where("goods",$goodsId)
                          ->orderBy("unit_sort")
                          ->first();
                
                for($b = 0;$b < count($warehouseName);$b++){
                    $warehouse[$b]->unit = $unit->unit;
                    $warehouse[$b]->qty = 0;
                    $warehouse[$b]->warehouseId = $warehouseId[$b];
                    $warehouse[$b]->warehouseName = $warehouseName[$b];
                    $warehouse[$b]->total = "0".$unit->unit;
                }
            }
            $goods->warehouse = $warehouse;
            $entryShop = new EntryShop();
            $property = $entryShop->getShopThreeAtt($shopId);
            $goods->goodsPropertyOne = $property->goodsPropertyOne;
            $goods->goodsPropertyTwo = $property->goodsPropertyTwo;
            $goods->goodsPropertyThree = $property->goodsPropertyThree;
        }
        
        return $goods;
    }

    //二维商品详情 -- 小程序
    public function findGoodsByDimensionalIdAndshopId($dimensionalId,$shopId){
        $goods = DB::table("base_goods_dimensional as bgd")
                   ->join("base_goods as bg","bgd.goods","=","bg.id")
                   ->select("bgd.id","bg.name","bg.property_title1 as propertyTitle1","bg.property_title2 as propertyTitle2","bgd.property_value1 as propertyValue1","bgd.property_value2 as propertyValue2","bgd.image","bgd.remark","bg.id as goodsId","bg.enterprise as enterpriseId","bg.goods_unit_group as goodsUnitGroup","bg.property1","bg.property2","bg.property3")
                   ->where("bgd.id",$dimensionalId)
                   ->first();
        $entryWarehouse = new EntryWarehouse();
        $warehouseList = $entryWarehouse -> warehouseListByShop($goods->enterpriseId,$shopId,null,null);
        $warehouseId = [];
        $warehouseName = [];
        for($m = 0;$m < count($warehouseList);$m++){
            $warehouseId[] = $warehouseList[$m]->warehouseId;
            $warehouseName[] = $warehouseList[$m]->warehouseName;
        }
        if(!empty($goods)){
            $goods->image = $goods->image?$goods->image:(Config::get("app.url").Config::get("app.goods_default_pic"));
            $goods->remark = $goods->remark?$goods->remark:"";
            //单位
            $entryUnit = new EntryUnit();
            $unitList = $entryUnit->barcodeListByDimensionalId($goods->goodsId,$dimensionalId);
            if(!$unitList){
                $unitList = $entryUnit->barcodeListByGoodsId($goods->goodsId);
            }

            for($a = 0;$a < count($unitList);$a++){
                $unitList[$a]["limitPrice"] = doubleval($unitList[$a]["limitPrice"]);
                $unitList[$a]["retailPrice"] = doubleval($unitList[$a]["retailPrice"]);
                $unitList[$a]["retailPrice1"] = doubleval($unitList[$a]["retailPrice1"]);
                $unitList[$a]["tradePrice"] = doubleval($unitList[$a]["tradePrice"]);
                $unitList[$a]["costPrice"] = doubleval($unitList[$a]["costPrice"]);
            }
            $goods->unitList = $unitList;
                    
            //总库存
            $goods->totalQty = $entryWarehouse -> findQtyByDimensionalId($goods->goodsId,$dimensionalId,$warehouseId);
            
            //库存详情
            $warehouseArr = DB::table("biz_warehouse_relation as bwr")
                              ->select("bwr.qty","bwr.unit","bwr.goods_unit_id as goodsUnitId","bwr.warehouse_id as warehouseId","bw.warehouse_name as warehouseName")
                              ->join("base_warehouse as bw","bwr.warehouse_id","=","bw.id")
                              ->where("bwr.goods",$goods->goodsId)
                              ->where("bwr.dimensional_goods_id",$dimensionalId)
                              ->where("bwr.shop",$shopId)
                              ->whereIn("bwr.warehouse_id",$warehouseId)
                              ->orderBy("bwr.unit_sort")
                              ->orderBy("bwr.warehouse_id","asc")
                              ->get();
            
            $warehouse = array();
            if(!empty($warehouseArr)){
                for($i = 0;$i < count($warehouseArr);$i++){
                    $warehouseArr[$i]->qty = doubleval($warehouseArr[$i]->qty);
                    if($i==0){
                        $warehouse[0]->unit = $warehouseArr[$i]->unit;
                        $warehouse[0]->qty = $warehouseArr[$i]->qty;
                        $warehouse[0]->warehouseId = $warehouseArr[$i]->warehouseId;
                        $warehouse[0]->warehouseName = $warehouseArr[$i]->warehouseName;
                        $warehouse[0]->total = $warehouseArr[$i]->qty.$warehouseArr[$i]->unit;
                    }else{
                        $newArr = 0;
                        for($j = 0;$j < count($warehouse);$j++){
                            if($warehouseArr[$i]->warehouseId == $warehouse[$j]->warehouseId){
                                $warehouse[$j]->total = $warehouseArr[$i]->qty.$warehouseArr[$i]->unit.$warehouse[$j]["total"];
                                $newArr = 1;
                            }
                        }
                        if(!$newArr){
                            $num = count($warehouse);
                            $warehouse[$num]->unit = $warehouseArr[$i]->unit;
                            $warehouse[$num]->qty = $warehouseArr[$i]->qty;
                            $warehouse[$num]->warehouseId = $warehouseArr[$i]->warehouseId;
                            $warehouse[$num]->warehouseName = $warehouseArr[$i]->warehouseName;
                            $warehouse[$num]->total = $warehouseArr[$i]->qty.$warehouseArr[$i]->unit;
                        }
                    }
                }
            }else{
                $unit = DB::table("biz_goods_unit_info")
                          ->select("unit")
                          ->where("goods",$goods->goodsId)
                          ->where("dimensional_id",$dimensionalId)
                          ->orderBy("unit_sort")
                          ->first();
                if(empty($unit)){
                    $unitName = "";
                }else{
                    $unitName = $unit->unit;
                }

                for($b = 0;$b < count($warehouseName);$b++){
                    $warehouse[$b]->unit = $unitName;
                    $warehouse[$b]->qty = 0;
                    $warehouse[$b]->warehouseId = $warehouseId[$b];
                    $warehouse[$b]->warehouseName = $warehouseName[$b];
                    $warehouse[$b]->total = "0".$unitName;
                }
            }
            $goods->warehouse = $warehouse;
            $entryShop = new EntryShop();
            $property = $entryShop->getShopThreeAtt($shopId);
            $goods->goodsPropertyOne = $property->goodsPropertyOne;
            $goods->goodsPropertyTwo = $property->goodsPropertyTwo;
            $goods->goodsPropertyThree = $property->goodsPropertyThree;
        }
        
        return $goods;
    }
    
    /** 
      * 函数名: findGoodsByScanCodeAndEnterpriseId
      * 用途: 根据企业ID和条形码查询商品详情
      *
      * @access public 
      * @param enterpriseId 企业ID
      * @param shopId 店铺ID
      * @param scanCode 商品条形码
      * @return json 
    */ 
    public function findGoodsByScanCodeAndEnterpriseId($barcode,$enterpriseId,$shopId){
        $goods = DB::table("base_goods as bg")
                   ->select("bg.id","bg.name as name","bg.enterprise as enterpriseId","bg.property1","bg.property2","bg.property3","bg.image","bg.remark","bg.property_title1 as propertyTitle1","bg.property_title2 as propertyTitle2","bg.goods_unit_group as goodsUnitGroup")
                   ->leftJoin("biz_goods_unit_info as bgui","bg.id","=","bgui.goods")
                   ->where("bg.enterprise",$enterpriseId)
                   ->where("bgui.barcode",$barcode)
                   ->where("bg.is_deleted",0)
                   ->first();
        if(!$goods){
            $goods = DB::table("base_goods_dimensional as bgd")
                       ->join("base_goods as bg","bgd.goods","=","bg.id")
                       ->leftJoin("biz_goods_unit_info as bgui","bgd.id","=","bgui.dimensional_id")
                       ->select("bgd.id","bg.name as name","bg.property_title1 as propertyTitle1","bg.property_title2 as propertyTitle2","bgd.property_value1 as propertyValue1","bgd.property_value2 as propertyValue2","bgd.image","bgd.remark","bg.id as goodsId","bg.enterprise as enterpriseId","bg.goods_unit_group as goodsUnitGroup","bg.property1","bg.property2","bg.property3")
                       ->where("bgd.enterprise",$enterpriseId)
                       ->where("bgui.barcode",$barcode)
                       ->where("bgd.is_deleted",0)
                       ->first();
        }
            
        $entryWarehouse = new EntryWarehouse();
        $warehouseList = $entryWarehouse -> warehouseListByShop($enterpriseId,$shopId,null,null);
        $warehouseId = [];
        $warehouseName = [];
        for($m = 0;$m < count($warehouseList);$m++){
            $warehouseId[] = $warehouseList[$m]->warehouseId;
            $warehouseName[] = $warehouseList[$m]->warehouseName;
        }
        if(!empty($goods)){
            $goods->image = $goods->image?$goods->image:(Config::get("app.url").Config::get("app.goods_default_pic"));
            $goods->remark = $goods->remark?$goods->remark:"";
            //单位
            $entryUnit = new EntryUnit();
            
            //库存详情
            $sql = DB::table("biz_warehouse_relation as bwr")
                     ->select("bwr.qty","bwr.unit","bwr.goods_unit_id as goodsUnitId","bwr.warehouse_id as warehouseId","bw.warehouse_name as warehouseName")
                     ->join("base_warehouse as bw","bwr.warehouse_id","=","bw.id")
                     ->where("bwr.shop",$shopId);
            if($goods->goodsId){
                $sql->where("bwr.goods",$goods->goodsId)
                    ->where("bwr.dimensional_goods_id",$dimensionalId);
                
                $unitList = $entryUnit->barcodeListByDimensionalId($goods->goodsId,$goods->id);
                if(!$unitList){
                    $unitList = $entryUnit->barcodeListByGoodsId($goods->goodsId);
                }
                //总库存
                $goods->totalQty = $entryWarehouse -> findQtyByDimensionalId($goods->goodsId,$goods->id,$warehouseId);
            }else{
                $sql->where("bwr.goods",$goods->id)
                    ->where("bwr.shop",$shopId)
                    ->where(function($sql){
                            $sql->where("bwr.dimensional_goods_id",null)
                                ->orWhere("bwr.dimensional_goods_id","");
                    });
                $unitList = $entryUnit->barcodeListByGoodsId($goods->id);
                $goods->totalQty = $entryWarehouse -> findQtyByGoodsId($goods->id,$warehouseId);
            }
            
            for($a = 0;$a < count($unitList);$a++){
                $unitList[$a]["limitPrice"] = doubleval($unitList[$a]["limitPrice"]);
                $unitList[$a]["retailPrice"] = doubleval($unitList[$a]["retailPrice"]);
                $unitList[$a]["retailPrice1"] = doubleval($unitList[$a]["retailPrice1"]);
                $unitList[$a]["tradePrice"] = doubleval($unitList[$a]["tradePrice"]);
                $unitList[$a]["costPrice"] = doubleval($unitList[$a]["costPrice"]);
            }
            $goods->unitList = $unitList;

            $warehouseArr = $sql->whereIn("bwr.warehouse_id",$warehouseId)
                                ->orderBy("bwr.unit_sort")
                                ->orderBy("bwr.warehouse_id","asc")
                                ->get();
            
            $warehouse = array();
            if(!empty($warehouseArr)){
                for($i = 0;$i < count($warehouseArr);$i++){
                    $warehouseArr[$i]->qty = doubleval($warehouseArr[$i]->qty);
                    if($i==0){
                        $warehouse[0]->unit = $warehouseArr[$i]->unit;
                        $warehouse[0]->qty = $warehouseArr[$i]->qty;
                        $warehouse[0]->warehouseId = $warehouseArr[$i]->warehouseId;
                        $warehouse[0]->warehouseName = $warehouseArr[$i]->warehouseName;
                        $warehouse[0]->total = $warehouseArr[$i]->qty.$warehouseArr[$i]->unit;
                    }else{
                        $newArr = 0;
                        for($j = 0;$j < count($warehouse);$j++){
                            if($warehouseArr[$i]->warehouseId == $warehouse[$j]->warehouseId){
                                $warehouse[$j]->total = $warehouseArr[$i]->qty.$warehouseArr[$i]->unit.$warehouse[$j]->total;
                                $newArr = 1;
                            }
                        }
                        if(!$newArr){
                            $num = count($warehouse);
                            $warehouse[$num]->unit = $warehouseArr[$i]->unit;
                            $warehouse[$num]->qty = $warehouseArr[$i]->qty;
                            $warehouse[$num]->warehouseId = $warehouseArr[$i]->warehouseId;
                            $warehouse[$num]->warehouseName = $warehouseArr[$i]->warehouseName;
                            $warehouse[$num]->total = $warehouseArr[$i]->qty.$warehouseArr[$i]->unit;
                        }
                    }
                }
            }else{
                if($goods->goodsId){
                    $unit = DB::table("biz_goods_unit_info")
                              ->select("unit")
                              ->where("goods",$goods->goodsId)
                              ->where("dimensional_id",$goods->id)
                              ->orderBy("unit_sort")
                              ->first();
                }else{
                    $unit = DB::table("biz_goods_unit_info")
                              ->select("unit")
                              ->where("goods",$goods->id)
                              ->orderBy("unit_sort")
                              ->first();
                }
                
                if(empty($unit)){
                    $unitName = "";
                }else{
                    $unitName = $unit->unit;
                }

                for($b = 0;$b < count($warehouseName);$b++){
                    $warehouse[$b]->unit = $unitName;
                    $warehouse[$b]->qty = 0;
                    $warehouse[$b]->warehouseId = $warehouseId[$b];
                    $warehouse[$b]->warehouseName = $warehouseName[$b];
                    $warehouse[$b]->total = "0".$unitName;
                }
            }
            $goods->warehouse = $warehouse;
            $entryShop = new EntryShop();
            $property = $entryShop->getShopThreeAtt($shopId);
            $goods->goodsPropertyOne = $property->goodsPropertyOne;
            $goods->goodsPropertyTwo = $property->goodsPropertyTwo;
            $goods->goodsPropertyThree = $property->goodsPropertyThree;
        }
        
        return $goods;
    }
    
    /** 
      * 函数名: goodsUnsalableByTime
      * 用途: 滞销商品列表
      *
      * @access public 
      * @param enterpriseId 企业Id
      * @param shopId 店铺Id
      * @param orderDate 订单时间点
      * @param offset 开始条数
      * @param rows 行数
      * @return array 
    */ 
    public function goodsUnsalableByTime($enterpriseId,$shopId,$orderDate,$offset,$rows){
        $sql = "
                SELECT
                    `tmp_goods`.`tmp_goods_goods_id` AS `goodsId`,
                    IFNULL(`tmp_goods`.`tmp_goods_dimensional_id`,'') AS `dimensionalId`,
                    `tmp_goods`.`goods_name` AS `goodsName`,
                    IFNULL(`tmp_goods`.`property_value1`,'') AS `propertyValue1`,
                    IFNULL(`tmp_goods`.`property_value2`,'') AS `propertyValue2`,
                    IFNULL(`tmp_order`.`order_date`,'') AS `orderDate`,
                    IFNULL(`tmp_order2`.`order_date`,'') AS `refundDate`
                FROM
                    (
                        SELECT
                            `bg`.`id` AS `tmp_goods_goods_id`,
                            ifnull(`bgd`.`id`, '') AS `tmp_goods_dimensional_id`,
                            `bg`.`name` AS `goods_name`,
                            `bgd`.`property_value1`,
                            `bgd`.`property_value2`
                        FROM
                            `base_goods` AS `bg`
                        LEFT JOIN `base_goods_dimensional` AS `bgd` ON `bg`.`id` = `bgd`.`goods` AND `bgd`.`is_deleted` = 0
                        WHERE
                            `bg`.`enterprise` = '".$enterpriseId."'
                        AND `bg`.`shop` = '".$shopId."'
                        AND `bg`.`is_deleted` = 0
                    ) AS `tmp_goods`
                LEFT JOIN (
                    SELECT
                        `bod`.`goods` AS `tmp_order_goods_id`,
                        `bo`.`is_return`,
                        IFNULL(`bod`.`dimensional_id`, '') AS `tmp_order_dimensional_id`,
                        FROM_UNIXTIME(`bo`.`order_date`/1000,'%Y-%m-%d %H:%i:%s') as `order_date`
                    FROM
                        `biz_order_dtl` AS `bod`
                    JOIN `biz_order` AS `bo` ON `bod`.`order` = `bo`.`id`
                    WHERE
                        `bod`.`enterprise` = '".$enterpriseId."'
                    AND `bod`.`shop` = '".$shopId."'
                    AND `bo`.`is_deleted` = 0
                    AND `bo`.`is_return` = 0
                    GROUP BY
                        `bod`.`goods`,
                        ifnull(`bod`.`dimensional_id`, ''),
                        `bo`.`is_return`
                ) AS `tmp_order` ON `tmp_goods`.`tmp_goods_goods_id` = `tmp_order`.`tmp_order_goods_id`
                AND `tmp_goods`.`tmp_goods_dimensional_id` = `tmp_order`.`tmp_order_dimensional_id`
                LEFT JOIN (
                    SELECT
                        `bod`.`goods` AS `tmp_order2_goods_id`,
                        `bo`.`is_return`,
                        IFNULL(`bod`.`dimensional_id`, '') AS `tmp_order2_dimensional_id`,
                        FROM_UNIXTIME(`bo`.`order_date`/1000,'%Y-%m-%d %H:%i:%s') as `order_date`
                    FROM
                        `biz_order_dtl` AS `bod`
                    JOIN `biz_order` AS `bo` ON `bod`.`order` = `bo`.`id`
                    WHERE
                        `bod`.`enterprise` = '".$enterpriseId."'
                    AND `bod`.`shop` = '".$shopId."'
                    AND `bo`.`is_deleted` = 0
                    AND `bo`.`is_return` = 1
                    GROUP BY
                        `bod`.`goods`,
                        ifnull(`bod`.`dimensional_id`, ''),
                        `bo`.`is_return`
                ) AS `tmp_order2`  ON `tmp_order`.`tmp_order_goods_id` = `tmp_order2`.`tmp_order2_goods_id`
                AND `tmp_order`.`tmp_order_dimensional_id` = `tmp_order2`.`tmp_order2_dimensional_id`
                WHERE UNIX_TIMESTAMP(IFNULL(`tmp_order`.`order_date`,'2015-01-01 00:00:00'))*1000 < '".$orderDate."'
                ORDER BY
                    `orderDate` ASC
                LIMIT ".$rows."
                OFFSET ".$offset;
        $results = DB::select($sql, []);
        return $results;
    }
    
    /** 
      * 函数名: goodsUpDownLimitByWarehouseId
      * 用途: 商品上下限列表
      *
      * @access public 
      * @param enterpriseId 企业Id
      * @param shopId 店铺Id
      * @param warehouseId 仓库Id
      * @param goodsName 商品名称
      * @param limitType 限度类型，up为上限,其他默认为下限
      * @param limitNum 上下限数额
      * @param offset 开始条数
      * @param rows 行数
      * @return array 
    */ 
    public function goodsUpDownLimitByWarehouseId($enterpriseId,$shopId,$warehouseId,$goodsName,$limitType,$limitNum,$offset,$rows){
        $sql = "
                SELECT
                    `tmp_goods`.`tmp_goods_goods_id` as `goodsId`,
                    `tmp_goods`.`tmp_goods_dimensional_id` as `dimensionalId`,
                    `tmp_goods`.`goods_name` as `goodsName`,
                    `tmp_goods`.`property_value1` as `propertyValue1`,
                    `tmp_goods`.`property_value2` as `propertyValue2`,
                    `bgui`.`id` AS `unitInfoId`,
                    `bgui`.`goods_unit_id` AS `unitId`,
                    `bgui`.`unit` AS `unitName`,
                    `bgui`.`uplimit_qty` AS `uplimitQty`,
                    `bgui`.`downlimit_qty` AS `downlimitQty`,
                    IFNULL(`tmp_warehouse`.`qty`, 0) AS `qty`,
                    IFNULL((`tmp_warehouse`.`qty` - `bgui`.`uplimit_qty`),0) as `upLimit`,
                    IFNULL((`tmp_warehouse`.`qty` - `bgui`.`downlimit_qty`),0) as `downLimit`
                FROM
                    (
                        SELECT
                            `bg`.`id` AS `tmp_goods_goods_id`,
                            ifnull(`bgd`.`id`, '') AS `tmp_goods_dimensional_id`,
                            `bg`.`name` AS `goods_name`,
                            ifnull(`bgd`.`property_value1`, '') AS `property_value1`,
                            ifnull(`bgd`.`property_value2`, '') AS `property_value2`
                        FROM
                            `base_goods` AS `bg`
                        LEFT JOIN `base_goods_dimensional` AS `bgd` ON `bg`.`id` = `bgd`.`goods`
                        AND `bgd`.`is_deleted` = 0
                        WHERE
                            `bg`.`enterprise` = '".$enterpriseId."'
                        AND `bg`.`shop` = '".$shopId."'
                        AND `bg`.`is_deleted` = 0
                    ) AS `tmp_goods`
                LEFT JOIN (
                            select * from biz_goods_unit_info as bgui2
                                where bgui2.dimensional_id is null
                                    or bgui2.dimensional_id = ''
                        ) AS `bgui` ON `tmp_goods`.`tmp_goods_goods_id` = `bgui`.`goods`
                LEFT JOIN (
                    SELECT
                        `bwr`.`goods`,
                        IFNULL(
                            `bwr`.`dimensional_goods_id`,
                            ''
                        ) AS `dimensional_id`,
                        `bwr`.`goods_unit_id`,
                        `bwr`.`qty`
                    FROM
                        `biz_warehouse_relation` AS `bwr`
                    WHERE
                        `bwr`.`enterprise` = '".$enterpriseId."'
                    AND `bwr`.`shop` = '".$shopId."'
                    AND `bwr`.`is_deleted` = 0
                    AND `bwr`.`warehouse_id` = '".$warehouseId."'
                ) AS `tmp_warehouse` ON `tmp_goods`.`tmp_goods_goods_id` = `tmp_warehouse`.`goods`
                AND `tmp_goods`.`tmp_goods_dimensional_id` = `tmp_warehouse`.`dimensional_id`
                AND `bgui`.`goods_unit_id` = `tmp_warehouse`.`goods_unit_id`
                WHERE `bgui`.`is_deleted` = 0";
        if($goodsName){
            $sql .= " AND `tmp_goods`.`goods_name` like '%".$goodsName."%'";
        }
        if($limitType == 'up'){
            if($limitNum != null){
                $sql .= " AND `bgui`.`uplimit_qty` >= ".$limitNum;
            }
            $sql .= " ORDER BY `upLimit` ASC";
        }else{
            if($limitNum != null){
                $sql .= " AND `bgui`.`downlimit_qty` <= ".$limitNum;
            }
            $sql .= " ORDER BY `downLimit` ASC";
        }
        $sql .= " LIMIT ".$rows." OFFSET ".$offset;
        $results = DB::select($sql, []);
        return $results;
    }
    
    //新增二维商品大商品
    public function addDimensionalGoods($enterpriseId,$shopId,$createName,$data,$unitMaxVersion,$goodsMaxVersion,$goodsUnitPriceMaxVersion,$warehouseRelationMaxVersion,$inoutDtlMaxVersion){
        //新建单位
        $unitName = $data[4];
        $groupId = EntryUnit::addMoreUnitList($enterpriseId,$shopId,$unitMaxVersion,$createName,$unitName);

        $unitList = EntryUnit::getUnitGroupList($groupId);
        $unitInfo = $unitList[0];

        //新建商品
        $name = $data[0];
        $property1 = $data[1];
        $property2 = $data[2];
        $property3 = $data[3];
        $goodsUnitGroup = $groupId;
        $remark = $data[12];
        $propertyTitle1 = $data[5];
        $propertyTitle2 = $data[6];
        
        $goodsId = EntryGoods::addGoodsData($enterpriseId,$shopId,$createName,$goodsMaxVersion,$name,$property1,$property2,$property3,$goodsUnitGroup,0,0,0,1,null,null,$propertyTitle1,$propertyTitle2,$remark);

        //新建商品单位价格信息
        $goodsUnitId = $unitInfo->id;
        $goodsUnitSort = $unitInfo->sort;
        $unitName = $unitInfo->name;
        $limitPrice = $data[11];
        $retailPrice = $data[8];
        $retailPrice1 = $data[9];
        $tradePrice = $data[7];
        $costPrice = $data[10];
        $barcode = $data[12];
        $barcode1 = $data[13];
        EntryGoodsUnitPrice::addGoodsUnitPrice($enterpriseId,$shopId,$createName,$goodsUnitPriceMaxVersion,$goodsId,null,$goodsUnitId,$unitName,$goodsUnitSort,0,0,$limitPrice,$retailPrice,$retailPrice1,$tradePrice,$costPrice,$barcode,$barcode1);
        
        for ($unitIndex = 1; $unitIndex < count($unitList); $unitIndex++) { 
            # code...
            $goodsUnitId = $unitList[$unitIndex]->id;
            $goodsUnitSort = $unitList[$unitIndex]->sort;
            $unitName = $unitList[$unitIndex]->name;
            $limit_price = '0';
            $retail_price = '0';
            $retail_price1 = '0';
            $trade_price = '0';
            $cost_price = '0';
            $barcode = '';
            $barcode1 = '';
            EntryGoodsUnitPrice::addGoodsUnitPrice($enterpriseId,$shopId,$createName,$goodsUnitPriceMaxVersion,$goodsId,null,$goodsUnitId,$unitName,$goodsUnitSort,0,0,$limit_price,$retail_price,$retail_price1,$trade_price,$cost_price,$barcode,$barcode1);
        }


        return $goodsId;
    }
    
    //二维商品新增
    public function addDimensionalGoodsInfo($enterpriseId,$shopId,$goodsId,$createName,$data,$unitMaxVersion,$goodsMaxVersion,$goodsUnitPriceMaxVersion,$warehouseRelationMaxVersion,$inoutDtlMaxVersion,$warehouseList){
        //新建单位
        $unitName = $data[0];

        $groupId = EntryUnit::addMoreUnitList($enterpriseId,$shopId,$unitMaxVersion,$createName,$unitName);

        $unitList = EntryUnit::getUnitGroupList($groupId);
        $unitInfo = $unitList[0];
        //新建商品
        $propertyValue1 = $data[1];
        $propertyValue2 = $data[2];
        $remark = $data[10];
        $goodsDimensionalMaxVersion = max_version("base_goods_dimensional",$enterpriseId)+1;
        $dimensionalId = EntryGoods::addGoodsDimensional($enterpriseId,$shopId,$createName,$goodsDimensionalMaxVersion,$goodsId,$propertyValue1,$propertyValue2,null,$remark);
        
        if (!empty(trim($data[3]))) {
            //新建商品单位价格信息
            $goodsUnitId = $unitInfo->id;
            $goodsUnitSort = $unitInfo->sort;
            $unitName = $unitInfo->name;
            $limitPrice = $data[7];
            $retailPrice = $data[4];
            $retailPrice1 = $data[5];
            $tradePrice = $data[3];
            $costPrice = $data[6];
            $barcode = $data[8];
            $barcode1 = $data[9];

            EntryGoodsUnitPrice::addGoodsUnitPrice($enterpriseId,$shopId,$createName,$goodsUnitPriceMaxVersion,$goodsId,$dimensionalId,$goodsUnitId,$unitName,$goodsUnitSort,0,0,$limitPrice,$retailPrice,$retailPrice1,$tradePrice,$costPrice,$barcode,$barcode1);

            for ($unitIndex = 1; $unitIndex < count($unitList); $unitIndex++) { 
                # code...
                $goodsUnitId2 = $unitList[$unitIndex]->id;
                $goodsUnitSort2 = $unitList[$unitIndex]->sort;
                $unitName2 = $unitList[$unitIndex]->name;
                $limit_price = '0';
                $retail_price = '0';
                $retail_price1 = '0';
                $trade_price = '0';
                $cost_price = '0';
                $barcode = '';
                $barcode1 = '';
                EntryGoodsUnitPrice::addGoodsUnitPrice($enterpriseId,$shopId,$createName,$goodsUnitPriceMaxVersion,$goodsId,null,$goodsUnitId2,$unitName2,$goodsUnitSort2,0,0,$limit_price,$retail_price,$retail_price1,$trade_price,$cost_price,$barcode,$barcode1);
            }
        }
        
        //新建商品库存.循环添加库存
        $goodsUnitId = $unitInfo->id;
        $goodsUnitSort = $unitInfo->sort;
        $unitName = $unitInfo->name;
        $index = 11;
        foreach ($warehouseList as $key => $value) {
            $qty = $data[$index];
            EntryWarehouseRelation::saveWarehouseRelation($enterpriseId,$shopId,$createName,$warehouseRelationMaxVersion,$inoutDtlMaxVersion,$value,$goodsId,$dimensionalId,$goodsUnitId,$unitName,$goodsUnitSort,$qty,null,null,null,null,'stockBegin');
            $index++;
        }
        return $dimensionalId;
    }
}