<?php namespace App\Models;
use Config;
use DB;
use Excel;
use Request;
use App\Models\EntryGoods;
use App\Models\EntryShop;
use App\Models\EntryGoodsBetchopLog;
use App\Services\BaiduPush;
use Illuminate\Database\Eloquent\Model;
class EntryFile extends Model {
    
    function goodsBetchAdd($filePath,$enterpriseId,$shopId,$name,$uid){
        $data = [];
        Excel::load($filePath, function($reader) use($shopId,$enterpriseId,$name,$uid,&$data){
            $entryShop = new EntryShop;
            $property = $entryShop->getShopThreeAtt($shopId);
            $goodsPropertyOne = $property->goodsPropertyOne;
            $goodsPropertyTwo = $property->goodsPropertyTwo;
            $goodsPropertyThree = $property->goodsPropertyThree;
            $reader = $reader->getSheet(0);//获取excel的第1张表
            $results = $reader->toArray();//获取表中的数据
            
            if(empty($results) || count($results) == 1){
                $data = array("status" => 0,"msg" => "上传文件没数据");
                return false;
            }
            $head = $results[0];
            $head = array_filter($head);
            $headArr = array_slice($head, 4, 9);
            $headFormat = Array ("商品单位","批发价","零售价1","零售价2","成本价","最低价","商品条形码1","商品条形码2","备注");
            if($headArr != $headFormat){
                $data = array("status" => 0,"msg" => "第一栏格式不正确");
                return false;
            }
            $warehouseNameList = array_slice($head,13);
            $warehouseList = [];
            for($a = 0;$a < count($warehouseNameList);$a++){
                
                $warehouseId = DB::table("biz_warehouse_permission as bwp")
                                 ->leftJoin("base_warehouse as bw","bwp.warehouse_id","=","bw.id")
                                 ->where("bwp.enterprise",$enterpriseId)
                                 ->where("bwp.shop",$shopId)
                                 ->where("bw.warehouse_name",$warehouseNameList[$a])
                                 ->pluck("bw.id");
                                 
                if(empty($warehouseId)){
                    $data = array("status" => 0,"msg" => "第".($a+1)."个仓库名称有误");
                    return false;
                }else{
                    $warehouseList[] = $warehouseId;
                }
            }
            
            $unitMaxVersion = max_version("biz_goods_unit",$enterpriseId)+1;
            $goodsMaxVersion = max_version("base_goods",$enterpriseId)+1;
            $goodsUnitPriceMaxVersion = max_version("biz_goods_unit_info",$enterpriseId)+1;
            $warehouseRelationMaxVersion = max_version("biz_warehouse_relation",$enterpriseId)+1;
            $inoutDtlMaxVersion =  max_version("biz_inout_dtl",$enterpriseId)+1;
            
            $logArr = array();
                
            //仓库个数
            $warehouseNum = count($warehouseList);
            
            DB::beginTransaction();
            //数据行数是以第2行开始
            for($row = 1; $row < count($results); $row++){
                $results[$row] = array_slice($results[$row], 0, (13+$warehouseNum));
                
                for($i = 1;$i < $warehouseNum+1;$i++){
                    $num = 12+$i;
                    if(empty($results[$row][$num])){
                        $results[$row][$num] = 0;
                    }
                    if(!is_numeric($results[$row][$num])){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".($row+1)."行第".($i)."个仓库库存数据有误");
                        return false;
                    }
                }
                $results[$row][0] = trim($results[$row][0]);
                $results[$row][1] = trim($results[$row][1]);
                $results[$row][2] = trim($results[$row][2]);
                $results[$row][3] = trim($results[$row][3]);
                $results[$row][4] = trim($results[$row][4]);
                $results[$row][4] = trim($results[$row][4]);
                $results[$row][10] = trim($results[$row][10]);
                $results[$row][11] = trim($results[$row][11]);
                $results[$row][12] = trim($results[$row][12]);
                
                if(!$results[$row][0]){
                    //事务回滚
                    DB::rollback();
                    $data = array("status" => 0,"msg" => "第".$row."条数据商品名称为空,请重新检查上传");
                    return false;
                }
                if(!$results[$row][1]){
                    //事务回滚
                    DB::rollback();
                    $data = array("status" => 0,"msg" => "第".$row."条数据第一属性为空,请重新检查上传");
                    return false;
                }
                if(!is_numeric($results[$row][5])){
                    //事务回滚
                    DB::rollback();
                    $data = array("status" => 0,"msg" => "第".$row."条数据批发价格式有误,请重新检查上传");
                    return false;
                }
                if(!is_numeric($results[$row][6])){
                    //事务回滚
                    DB::rollback();
                    $data = array("status" => 0,"msg" => "第".$row."条数据零售价1格式有误,请重新检查上传");
                    return false;
                }
                if(!is_numeric($results[$row][7])){
                    //事务回滚
                    DB::rollback();
                    $data = array("status" => 0,"msg" => "第".$row."条数据零售价2格式有误,请重新检查上传");
                    return false;
                }
                if(!is_numeric($results[$row][8])){
                    //事务回滚
                    DB::rollback();
                    $data = array("status" => 0,"msg" => "第".$row."条数据成本价格式有误,请重新检查上传");
                    return false;
                }
                if(!is_numeric($results[$row][9])){
                    //事务回滚
                    DB::rollback();
                    $data = array("status" => 0,"msg" => "第".$row."条数据最低价格式有误,请重新检查上传");
                    return false;
                }

                //数据操作区域
                $rt = EntryGoods::addGoodsInfo($enterpriseId,$shopId,$name,$results[$row],$unitMaxVersion,$goodsMaxVersion,$goodsUnitPriceMaxVersion,$warehouseRelationMaxVersion,$inoutDtlMaxVersion,$warehouseList);
                if($rt){
                    $logArr[] = $rt;
                }else{
                    //事务回滚
                    DB::rollback();
                    $data = array("status" => 0,"msg" => "第".$row."条商品新增失败,请检查全部重新操作");
                    return false;
                }
            }
            if(empty($logArr)){
                $data = array("status" => 0,"msg" => "商品写入失败");
                return false;
            }else{
                //写日志开始
                $arrLog = array();
                $arrLog["content"] = "上传添加商品";
                $arrLog["create_date"] = get_ms();
                $arrLog["username"] = $name;
                $arrLog["uid"] = $uid;
                $arrLog["shop_id"] = $shopId;
                $arrLog["goods_list"] = json_encode($logArr);
                $arrLog["old_file"] = Request::file('onefile')->getClientOriginalName();
                $rtn = EntryGoodsBetchopLog::addLog($arrLog);
                if($rtn){
                    //事务提交
                    DB::commit();
                    $bs = new BaiduPush;
                    $bs->synPush($enterpriseId,$uid);
                    $data = array("status" => 1,"msg" => "批量添加成功,成功新增".(count($results)-1)."条数据");
                }else{
                    //事务回滚
                    DB::rollback();
                    $data = array("status" => 0,"msg" => $rtn["msg"]);
                }
            }
        });
        return $data;
    }
    
    
    //客户批量
    function customerBetchAdd($filePath,$enterpriseId,$name,$uid,$customerType){
        $data = [];
        Excel::load($filePath, function($reader) use($enterpriseId,$name,$uid,$filePath,$customerType,&$data){
            $customer = new EntryCustomer;
            $reader = $reader->getSheet(0);//获取excel的第1张表
            $results = $reader->toArray();//获取表中的数据
            if(empty($results) || count($results) == 1){
                $data = array("status" => 0,"msg" => "上传文件没数据");
                return false;
            }
            $arr_title = array("客户名称","手机号码","电话","邮箱","地址","客户分组","客户类型","欠款金额","备注");
            $header = $results[0];
            if($header != $arr_title){
                $data = array("status" => 0,"msg" => "文件表格格式不正确");
                return false;
            }
            
            $arr_ignore = array();
            $tmp = array();
            $tmp["is_deleted"] = 0;
            $tmp["create_date"] = get_ms();
            $tmp["modify_date"] = get_ms();
            $tmp["enterprise"] = $enterpriseId;
            $tmp["creator"] = $name;
            $tmp["editor"] = $name;
            $tmp["operator"] = $name;
            $tmp["version"] = max_version("base_customer",$enterpriseId)+1;
            $tmp["reserve1"] = "100";
            $groupVersion = max_version("base_customer_group",$enterpriseId)+1;
            if(count($results) > 1){
                if($customerType != "customer"){
                    $customerType = "supplier";
                }
                DB::beginTransaction();
                //数据行数是以第2行开始
                for($row = 1; $row < count($results); $row++){
                    $tmp["id"] = get_newid("base_customer");
                    $tmp["name"] = $results[$row][0];
                    $tmp["mobile"] = $results[$row][1];
                    $tmp["phone"] = $results[$row][2];
                    $tmp["email"] = $results[$row][3];
                    $tmp["address"] = $results[$row][4];
                    $tmp["group"] = $results[$row][5]?$results[$row][5]:"默认";
                    if($customerType == "customer"){
                        if($results[$row][6] == "批发"){
                            $tmp["type"] = "wholesale";
                        }else{
                            $tmp["type"] = "retail";
                        }
                    }else{
                        $tmp["type"] = "supplier";
                    }
                    $tmp["debt_money"] = $results[$row][7];
                    $tmp["remark"] = $results[$row][8];
                    
                    if(!trim($tmp["name"])){
                        $data = array("status" => 0,"msg" => "第".$row."位客户名称不可为空");
                        return false;
                    }
                    if(!$tmp["mobile"]){
                        $data = array("status" => 0,"msg" => "第".$row."位客户手机号码不能为空");
                        return false;
                    }
                    $isExit = $customer->findCstomerByMoblie($enterpriseId,$tmp["mobile"],$customerType);
                    $tmp["reserve2"] = null;
                    
                    $getGroupId = $customer->findGroupByName($enterpriseId,$tmp["group"],$customerType);
                    if($getGroupId){
                        $tmp["reserve2"] = $getGroupId;
                    }else{
                        $arr = array();
                        $arr["id"] = get_newid("base_customer_group");
                        $arr["is_deleted"] = 0;
                        $arr["create_date"] = get_ms();
                        $arr["modify_date"] = get_ms();
                        $arr["creator"] = $name;
                        $arr["editor"] = $name;
                        $arr["version"] = $groupVersion;//版本
                        $arr["enterprise"] = $enterpriseId;
                        $arr["shop"] = $enterpriseId;
                        $arr["group_name"] = $tmp["group"];
                        $arr["discount"] = 100;
                        $arr["customer_type"] = $customerType;
                        $addGroup = $customer->addCustomerGroup($arr);
                        if($addGroup){
                            $tmp["reserve2"] = $arr["id"];
                        }else{
                            DB::rollback();
                            $data = array("status" => 0,"msg" => "第".$row."位客户写入失败，请检查全部重新操作");
                            return false;
                        }
                    }
                    $return = $customer->addCustomer($tmp);
                    if(!$return){
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "客户写入失败");
                        return false;
                    }
                }
                DB::commit();
                $bs = new BaiduPush;
                $bs->synPush($enterpriseId,$uid);
                $data = array("status" => 1,"msg" => "成功录入".(count($results)-1)."条数据");
            }else{
                $data = array("status" => 0,"msg" => "用户已存在，忽略录入");
            }
        });
        return $data;
    }
    
    //二维商品批量上传
    function dimensionalBetchAdd($filePath,$enterpriseId,$shopId,$name,$uid){
        $data = [];
        Excel::load($filePath, function($reader) use($shopId,$enterpriseId,$name,$uid,&$data){
            $entryShop = new EntryShop;
            $property = $entryShop->getShopThreeAtt($shopId);
            $goodsPropertyOne = $property->goodsPropertyOne;
            $goodsPropertyTwo = $property->goodsPropertyTwo;
            $goodsPropertyThree = $property->goodsPropertyThree;
            $reader = $reader->getSheet(0);//获取excel的第1张表
            $results = $reader->toArray();//获取表中的数据
            if(empty($results) || count($results) == 1){
                $data = array("status" => 0,"msg" => "上传文件没数据");
                return false;
            }
            $head = array_filter($results[0]);
            $headArr = array_slice($head, 4, 11);
            $headFormat = Array ("商品单位","二维属性一","二维属性二","批发价","零售价1","零售价2","成本价","最低价","商品条形码1","商品条形码2","备注");
            if($headArr != $headFormat){
                $data = array("status" => 0,"msg" => "第一栏格式不正确");
                return false;
            }
            $warehouseNameList = array_slice($head,15);
            $warehouseList = [];
            for($a = 0;$a < count($warehouseNameList);$a++){
                $warehouseId = DB::table("biz_warehouse_permission as bwp")
                                 ->leftJoin("base_warehouse as bw","bwp.warehouse_id","=","bw.id")
                                 ->where("bwp.enterprise",$enterpriseId)
                                 ->where("bwp.shop",$shopId)
                                 ->where("bw.warehouse_name",$warehouseNameList[$a])
                                 ->pluck("bw.id");
                                 
                if(empty($warehouseId)){
                    $data = array("status" => 0,"msg" => "第".($a+1)."个仓库名称有误");
                    return false;
                }else{
                    $warehouseList[] = $warehouseId;
                }
            }
            
            $unitMaxVersion = max_version("biz_goods_unit",$enterpriseId)+1;
            $goodsMaxVersion = max_version("base_goods",$enterpriseId)+1;
            $goodsUnitPriceMaxVersion = max_version("biz_goods_unit_info",$enterpriseId)+1;
            $warehouseRelationMaxVersion = max_version("biz_warehouse_relation",$enterpriseId)+1;
            $inoutDtlMaxVersion =  max_version("biz_inout_dtl",$enterpriseId)+1;
            
            $logArr = array();
                
            //仓库个数
            $warehouseNum = count($warehouseList);
            
            DB::beginTransaction();
            //数据行数是以第2行开始
            for($row = 1; $row < count($results); $row++){
                $results[$row] = array_slice($results[$row], 0, (16+$warehouseNum));
                if(empty(trim($results[$row][0]))){
                    $arr[0] = trim($results[$row][4]);
                    $arr[1] = trim($results[$row][5]);
                    $arr[2] = trim($results[$row][6]);
                    $arr[3] = trim($results[$row][7]);
                    $arr[4] = trim($results[$row][8]);
                    $arr[5] = trim($results[$row][9]);
                    $arr[6] = trim($results[$row][10]);
                    $arr[7] = trim($results[$row][11]);
                    $arr[8] = trim($results[$row][12]);
                    $arr[9] = trim($results[$row][13]);
                    $arr[10] = trim($results[$row][14]);
                    for($i = 1;$i < $warehouseNum+1;$i++){
                        $num = 10+$i;
                        $arr[$num] = $results[$row][(14+$i)];
                        if(empty($arr[$num])){
                            $arr[$num] = 0;
                        }
                        if(!is_numeric($arr[$num])){
                            //事务回滚
                            DB::rollback();
                            $data = array("status" => 0,"msg" => "第".($row+1)."行第".($i)."个仓库库存数据有误");
                            return false;
                        }
                    }
                    if(!$arr[0]){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据单位为空,请重新检查上传");
                        return false;
                    }
                    if(!$arr[1]){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据二维属性一为空,请重新检查上传");
                        return false;
                    }
                    if(!$arr[2]){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据二维属性二为空,请重新检查上传");
                        return false;
                    }
                    if(!is_numeric($arr[3])){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据批发价格式有误,请重新检查上传");
                        return false;
                    }
                    if(!is_numeric($arr[4])){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据零售价1格式有误,请重新检查上传");
                        return false;
                    }
                    if(!is_numeric($arr[5])){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据零售价2格式有误,请重新检查上传");
                        return false;
                    }
                    if(!is_numeric($arr[6])){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据成本价格式有误,请重新检查上传");
                        return false;
                    }
                    if(!is_numeric($arr[7])){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据最低价格式有误,请重新检查上传");
                        return false;
                    }
                    //数据操作区域
                    $dimensionalId = EntryGoods::addDimensionalGoodsInfo($enterpriseId,$shopId,$goodsId,$userName,$arr,$unitMaxVersion,$goodsMaxVersion,$goodsUnitPriceMaxVersion,$warehouseRelationMaxVersion,$inoutDtlMaxVersion,$warehouseList);
                }else{
                    $results[$row][0] = trim($results[$row][0]);
                    $results[$row][1] = trim($results[$row][1]);
                    $results[$row][2] = trim($results[$row][2]);
                    $results[$row][3] = trim($results[$row][3]);
                    $results[$row][4] = trim($results[$row][4]);
                    $results[$row][5] = trim($results[$row][5]);
                    $results[$row][6] = trim($results[$row][6]);
                    $results[$row][7] = trim($results[$row][7]);
                    $results[$row][8] = trim($results[$row][8]);
                    $results[$row][9] = trim($results[$row][9]);
                    $results[$row][10] = trim($results[$row][10]);
                    $results[$row][11] = trim($results[$row][11]);
                    $results[$row][12] = trim($results[$row][12]);
                    $results[$row][13] = trim($results[$row][13]);
                    $results[$row][14] = trim($results[$row][14]);
                    if(!$results[$row][0]){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据商品名称为空,请重新检查上传");
                        return false;
                    }
                    if(!$results[$row][1]){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据属性一为空,请重新检查上传");
                        return false;
                    }
                    if(!$results[$row][4]){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据商品单位格式有误,请重新检查上传");
                        return false;
                    }
                    if(!$results[$row][5]){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据二维属性一格式有误,请重新检查上传");
                        return false;
                    }
                    if(!$results[$row][6]){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据二维属性二格式有误,请重新检查上传");
                        return false;
                    }
                    if(!is_numeric($results[$row][7])){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据批发价格式有误,请重新检查上传");
                        return false;
                    }
                    if(!is_numeric($results[$row][8])){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据零售价1格式有误,请重新检查上传");
                        return false;
                    }
                    if(!is_numeric($results[$row][9])){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据零售价2格式有误,请重新检查上传");
                        return false;
                    }
                    if(!is_numeric($results[$row][10])){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据成本价格式有误,请重新检查上传");
                        return false;
                    }
                    if(!is_numeric($results[$row][11])){
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条数据最低价格式有误,请重新检查上传");
                        return false;
                    }
                        
                    //数据操作区域
                    $goodsId = EntryGoods::addDimensionalGoods($enterpriseId,$shopId,$userName,$results[$row],$unitMaxVersion,$goodsMaxVersion,$goodsUnitPriceMaxVersion,$warehouseRelationMaxVersion,$inoutDtlMaxVersion);
                    if($goodsId){
                        $logArr[] = $goodsId;
                    }else{
                        //事务回滚
                        DB::rollback();
                        $data = array("status" => 0,"msg" => "第".$row."条商品新增失败,请检查全部重新操作");
                        return false;
                    }
                }
            }
            if(empty($logArr)){
                $data = array("status" => 0,"msg" => "商品写入失败");
                return false;
            }else{
                //写日志开始
                $arrLog = array();
                $arrLog["content"] = "上传添加商品";
                $arrLog["create_date"] = get_ms();
                $arrLog["username"] = $name;
                $arrLog["uid"] = $uid;
                $arrLog["shop_id"] = $shopId;
                $arrLog["goods_list"] = json_encode($logArr);
                $arrLog["old_file"] = Request::file('onefile')->getClientOriginalName();
                $rtn = EntryGoodsBetchopLog::addLog($arrLog);
                if($rtn){
                    //事务提交
                    DB::commit();
                    $bs = new BaiduPush;
                    $bs->synPush($enterpriseId,$uid);
                    $data = array("status" => 1,"msg" => "批量添加成功,成功新增".(count($results)-1)."条数据");
                }else{
                    //事务回滚
                    DB::rollback();
                    $data = array("status" => 0,"msg" => $rtn["msg"]);
                }
            }
        });
        return $data;
    }
}