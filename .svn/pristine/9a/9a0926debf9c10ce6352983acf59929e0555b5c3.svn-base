<?php namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
class EntryCustomerGoodsRelation extends Model {
    protected $table = 'biz_customer_goods_relation';
    public $timestamps = false;
    
    /** 
      * 函数名: findCustomerGoodsRelation
      * 用途: 新增客户商品关系
      *
      * @access public 
      * @param enterpriseId 企业Id
      * @param shopId 店铺Id
      * @param customerId 客户ID
      * @param goodsId 商品ID
      * @param dimensionalId 二维ID
      * @return array
    */
    public function findCustomerGoodsRelation($enterpriseId,$shopId,$customerId,$goodsId,$dimensionalId){
        $sql = DB::table("biz_customer_goods_relation")
                 ->select("id as relationId","customer as customerId","goods as goodsId","dimensional_id as dimensionalId","tip_days as tipDays")
                 ->where("enterprise",$enterpriseId)
                 ->where("shop",$shopId)
                 ->where("customer",$customerId)
                 ->where("goods",$goodsId)
                 ->where("is_deleted",0);
        if(!empty($dimensionalId)){
            $sql->where("dimensional_id",$dimensionalId);
        }
        $rt = $sql->first();
        return $rt;
    }
    
    /** 
      * 函数名: addCustomerGoodsRelation
      * 用途: 新增客户商品关系
      *
      * @access public 
      * @param enterpriseId 企业Id
      * @param shopId 店铺Id
      * @param userName 操作者
      * @param customerId 客户ID
      * @param goodsId 商品ID
      * @param dimensionalId 二维ID
      * @param tipDays 提醒天数
      * @return array
    */
    public function addCustomerGoodsRelation($enterpriseId,$shopId,$userName,$customerId,$goodsId,$dimensionalId,$tipDays){
        $arr["id"] = get_newid("biz_customer_goods_relation");
        $arr["is_deleted"] = 0;
        $arr["create_date"] = get_ms();
        $arr["modify_date"] = get_ms();
        $arr["creator"] = $userName;
        $arr["editor"] = $userName;
        $arr["version"] = max_version("biz_customer_goods_relation",$enterpriseId)+1;
        $arr["enterprise"] = $enterpriseId;
        $arr["shop"] = $shopId;
        $arr["customer"] = $customerId;
        $arr["goods"] = $goodsId;
        $arr["dimensional_id"] = $dimensionalId;
        $arr["tip_days"] = $tipDays;
        $rt = DB::table("biz_customer_goods_relation")->insert($arr);
        return $rt;
    }
    
    /** 
      * 函数名: findGoodsRelationByCustomerId
      * 用途: 根据用户ID查询客户商品关系列表
      *
      * @access public 
      * @param enterpriseId 企业Id
      * @param shopId 店铺Id
      * @param customerId 客户ID
      * @param keyword 关键字
      * @param offset 开始条数
      * @param rows 行数
      * @return array
    */
    public function findGoodsRelationByCustomerId($enterpriseId,$shopId,$customerId,$keyword,$offset,$rows){
        $query = DB::table("biz_customer_goods_relation as bcgr")
                   ->select("bcgr.id as relationId","bcgr.customer as customerId","bcgr.goods as goodsId","bcgr.dimensional_id as dimensionalId","bcgr.tip_days as tipDays","bg.name as goodsName","bg.is_bivariate as isBivariate","bgd.property_value1 as propertyValue1","bgd.property_value2 as propertyValue2")
                   ->leftJoin("base_goods as bg","bcgr.goods","=","bg.id")
                   ->leftJoin("base_goods_dimensional as bgd","bcgr.dimensional_id","=","bgd.id")
                   ->where("bcgr.enterprise",$enterpriseId)
                   ->where("bcgr.shop",$shopId)
                   ->where("bcgr.customer",$customerId)
                   ->where("bcgr.is_deleted",0)
                   ->where("bcgr.tip_days","<>",0);
        if($keyword){
            $kw = changeStr($keyword);
            $query->where("bg.name","like",$kw);
        }
        $rt = $query->orderBy("bcgr.modify_date","desc")
                    ->skip($offset)
                    ->take($rows)
                    ->get();
        return $rt;
    }
    
    /** 
      * 函数名: updateCustomerGoodsRelation
      * 用途: 修改客户商品关系
      *
      * @access public 
      * @param enterpriseId 企业Id
      * @param shopId 店铺Id
      * @param userName 操作者
      * @param customerId 客户ID
      * @param goodsId 商品ID
      * @param dimensionalId 二维ID
      * @param tipDays 提醒天数
      * @return array
    */
    public function updateCustomerGoodsRelation($enterpriseId,$userName,$relationId,$tipDays){
        $arr["modify_date"] = get_ms();
        $arr["editor"] = $userName;
        $arr["version"] = max_version("biz_customer_goods_relation",$enterpriseId)+1;
        $arr["tip_days"] = $tipDays;
        $rt = DB::table("biz_customer_goods_relation")
                ->where("id",$relationId)
                ->update($arr);
        return $rt;
    }
}