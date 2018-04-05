<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryUnit extends Model {
    protected $table = 'biz_goods_unit';
    public $timestamps = false;
        
    //获取单位列表
    static function unitList($enterpriseId,$page,$rows){
        $offset = ($page-1)*$rows;
        $unitList = DB::table("biz_goods_unit")
                      ->select(DB::raw('unit_group as groupId , group_concat(name ORDER BY `sort` asc) as unitName,group_concat(ratio ORDER BY `sort` asc separator ":") as unitRatio'))
                      ->where("is_deleted",0)
                      ->where("enterprise",$enterpriseId)
                      ->groupBy("unit_group")
                      ->orderBy("modify_date","desc")
                      ->skip($offset)
                      ->take($rows)
                      ->get();
        $unitList = obj2arr($unitList);
        return $unitList;
    }
    
    //根据分组id获取单位列表
    static function unitGroupList($groupId){
        $unitList = DB::table("biz_goods_unit")
                      ->select("id","name","sort","unit_group")
                      ->where("unit_group",$groupId)
                      ->get();
        $unitList = obj2arr($unitList);
        return $unitList;
    }

    //根据分组id获取单位列表
    public static function getUnitGroupList($groupId){
        $unitList = DB::table("biz_goods_unit")
                      ->select("id","name","sort","unit_group")
                      ->where("unit_group",$groupId)
                      ->orderBy("sort")
                      ->get();
        return $unitList;
    }
       
    //根据商品ID查询条形码
    static function barcodeListByGoodsId($goodsId){
        $barcodeList = DB::table("biz_goods_unit_info")
                         ->select("id as goodsUnitInfoId","goods_unit_id as goodsUnitId","barcode","barcode1","limit_price as limitPrice","retail_price as retailPrice","retail_price1 as retailPrice1","trade_price as tradePrice","cost_price as costPrice","unit")
                         ->where("goods",$goodsId)
                         ->where(function($sql){
                                $sql->where("dimensional_id",null)
                                    ->orWhere("dimensional_id","");
                            })
                         ->orderBy("unit_sort")
                         ->get();
        $barcodeList = obj2arr($barcodeList);
        $barcodeList = if_null($barcodeList);
        for($i = 0;$i < count($barcodeList);$i++){
            $barcodeList[$i]["limitPrice"] = doubleval($barcodeList[$i]["limitPrice"]);
            $barcodeList[$i]["retailPrice"] = doubleval($barcodeList[$i]["retailPrice"]);
            $barcodeList[$i]["retailPrice1"] = doubleval($barcodeList[$i]["retailPrice1"]);
            $barcodeList[$i]["tradePrice"] = doubleval($barcodeList[$i]["tradePrice"]);
            $barcodeList[$i]["costPrice"] = doubleval($barcodeList[$i]["costPrice"]);
        }
        return $barcodeList;
    }
    
    //根据商品ID查询单位价格信息-----二维
	static function barcodeListByDimensionalId($goodsId,$dimensionalId){
		$barcodeList = DB::table("biz_goods_unit_info")
                      ->select("id as goodsUnitInfoId","goods_unit_id as goodsUnitId","barcode","barcode1","limit_price as limitPrice","retail_price as retailPrice","retail_price1 as retailPrice1","trade_price as tradePrice","cost_price as costPrice","unit")
                      ->where("goods",$goodsId)
					  ->where("dimensional_id",$dimensionalId)
                      ->orderBy("unit_sort")
                      ->get();
        $barcodeList = obj2arr($barcodeList);
        //$barcodeList = if_null($barcodeList);
        for($i = 0;$i < count($barcodeList);$i++){
            $barcodeList[$i]["limitPrice"] = $barcodeList[$i]["limitPrice"]?doubleval($barcodeList[$i]["limitPrice"]):0;
            $barcodeList[$i]["retailPrice"] = $barcodeList[$i]["retailPrice"]?doubleval($barcodeList[$i]["retailPrice"]):0;
            $barcodeList[$i]["retailPrice1"] = $barcodeList[$i]["retailPrice1"]?doubleval($barcodeList[$i]["retailPrice1"]):0;
            $barcodeList[$i]["tradePrice"] = $barcodeList[$i]["tradePrice"]?doubleval($barcodeList[$i]["tradePrice"]):0;
            $barcodeList[$i]["costPrice"] = $barcodeList[$i]["costPrice"]?doubleval($barcodeList[$i]["costPrice"]):0;
			$barcodeList[$i]["barcode"] = $barcodeList[$i]["barcode"]?$barcodeList[$i]["barcode"]:"";
			$barcodeList[$i]["barcode1"] = $barcodeList[$i]["barcode1"]?$barcodeList[$i]["barcode1"]:"";
        }
        return $barcodeList;
	}

    public static function getUnitByUnitName($enterpriseId,$unitName){
        $sql = 'select bgu.* from biz_goods_unit as bgu
                      left join (
                        select unit_group
                          from biz_goods_unit
                          where enterprise = ?
                            and is_deleted = 0
                            and sort <> 1
                      )as tmp on bgu.unit_group = tmp.unit_group
                      where bgu.enterprise = ?
                        and bgu.name = ?
                        and bgu.sort = 1
                        and tmp.unit_group is null';
        $unitInfo = DB::select($sql,array($enterpriseId,$enterpriseId,$unitName));
        //print_r($unitInfo);
        if($unitInfo == null){
          return null;
        }
        return $unitInfo[0];
    }

    public static function addUnit($enterpriseId,$shopId,$unit_group,$maxVersion,$createName,$unitName,$ratio,$sort){
        $id = create_uuid();
        
        $time = get_ms();
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
          'unit_group'=>$unit_group,
          'name'=>$unitName,
          'ratio'=>$ratio,
          'sort'=>$sort
          );
        DB::table("biz_goods_unit")->insert($data);
        return $id;
    }

    public static function getUnitInfo($unitId){
        $unitInfo = DB::table("biz_goods_unit")
                      ->where("is_deleted",0)
                      ->where("id",$unitId)
                      ->first();
        return $unitInfo;
    }
	
	//根据分组id获取单位个数
    static function unitGroupListNum($groupId){
        $unitList = DB::table("biz_goods_unit")
                      ->where("is_deleted",0)
                      ->where("unit_group",$groupId)
                      ->count();
        return $unitList;
    }
    
    //单位分组总个数
    function countUnitList($enterpriseId){
        $unitNum = DB::table("biz_goods_unit")
                      ->select(DB::raw("count(distinct(unit_group)) as unitGroupNum"))
                      ->where("is_deleted",0)
                      ->where("enterprise",$enterpriseId)
                      ->first();

        return $unitNum->unitGroupNum;
    }
    
    //根据分组名称查询分组是否已存在
    function unitGroupIsExit($str,$enterpriseId){
        $sql = 'select COUNT(*) as isexit from
                    (
                    select group_concat(name ORDER BY `sort` asc) as unitName 
                        from biz_goods_unit 
                        where enterprise="'.$enterpriseId.'"
                            and is_deleted=0
                        GROUP BY unit_group
                    ) as bgu
                    where bgu.unitName = "'.$str.'"';
        $isExit = DB::select($sql, array());
        
        return $isExit[0]->isexit;
    }
    
    //单位分组列表
    function goodsUnitGroupList($enterpriseId,$page,$rows,$keyword){
        $offset = ($page-1)*$rows;
        $sql = "
                select tmp.groupId,tmp.unitName,tmp.unitRatio
                    from (
                        select unit_group as groupId , group_concat(name ORDER BY `sort` asc) as unitName,group_concat(ratio ORDER BY `sort` asc separator ':') as unitRatio
                            from biz_goods_unit
                            where is_deleted = 0
                              and enterprise = '".$enterpriseId."'
                              group by unit_group
                              order by modify_date desc
                        ) as tmp
                ";
        if($keyword){
            $sql .= "where tmp.unitName like '%".$keyword."%'";
        }
        $sql .= "limit ".$rows." offset ".$offset;
        $goodsUnitGroupList = DB::select($sql, []);
        return $goodsUnitGroupList;
    }
    
    //新增单位分组
    function addUnitGroup($arr){
        $rt = DB::table("biz_goods_unit")->insert($arr);
        return $rt;
    }

    public static function existUnitInfo($enterpriseId,$unitNameList,$unitSortList){
        $unitList = DB::select("select * from (select unit_group as groupId , group_concat(name ORDER BY `sort` asc) as unitName,group_concat(ratio ORDER BY `sort` asc separator ':') as unitRatio from `biz_goods_unit` where `is_deleted` = 0 and `enterprise` = '".$enterpriseId."' GROUP BY unit_group) as unitTable where unitTable.`unitName` = '".$unitNameList."' and unitTable.`unitRatio` = '".$unitSortList."'");
        return $unitList;
    }

    public static function addMoreUnitList($enterpriseId,$shopId,$unitMaxVersion,$createName,$unitName){
      $unitList = explode("/",$unitName);
      if (count($unitList) > 1) {
        $index = 0;
        $unitNameList = "";
        $unitSortList = "";
        foreach ($unitList as $key => $value) {
          $valueList = explode("-",$value);
          if($index == 0){
            $unitSortList = '1';
            $unitNameList = $valueList[0];
          }else{
            $unitSortList = $unitSortList.':'.$valueList[1];

            $unitNameList = $unitNameList.','.$valueList[0];
          }
          $index++;
        }
        $unitList2 = EntryUnit::existUnitInfo($enterpriseId,$unitNameList,$unitSortList);
        if(count($unitList2) > 0){
          return $unitList2[0]->groupId;
        }else{
          $index = 0;
          $unitGroup = create_uuid();
          foreach ($unitList as $key => $value) {
            $valueList = explode("-",$value);
            if($index == 0){
              $unitSortList = '1';
              $unitNameList = $valueList[0];
            }else{
              $unitSortList = $valueList[1];
              $unitNameList = $valueList[0];
            }
            $index++;
            $ratio = $unitSortList;
                  $sort = $index;
                  $unitId = EntryUnit::addUnit($enterpriseId,$shopId,$unitGroup,$unitMaxVersion,$createName,$unitNameList,$ratio,$sort);

                  print_r($unitId);
          }
          return $unitGroup;
        }
      }else{
        $valueList = explode("-",$unitName);
        //echo $valueList[0];
        //只有一个单位.直接保存
        $unitInfo = EntryUnit::getUnitByUnitName($enterpriseId,$valueList[0]);
            if(empty($unitInfo)){
                //添加单位,获取单位ID
                $ratio = 1;
                $sort = 1;
                $unitGroup = create_uuid();
                $unitId = EntryUnit::addUnit($enterpriseId,$shopId,$unitGroup,$unitMaxVersion,$createName,$valueList[0],$ratio,$sort);
                
                $unitInfo = EntryUnit::getUnitInfo($unitId);
            }
            return $unitInfo->unit_group;
      }
    }


}