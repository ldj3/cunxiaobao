<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryReportPush extends Model {
    protected $table = 'biz_report_push';
    public $timestamps = false;
        
    /** 
      * 函数名: reportPushList
      * 用途: 根据用户ID查询报表推送列表
      *
      * @access public 
      * @param userId 用户Id
      * @return array 
    */ 
    public function reportPushList($userId){
        $rt = DB::table("biz_report_push as brp")
                ->select("brp.report_id as reportId","brp.user_id as userId","brp.shop_id as shopId","brp.is_push as isPush","brp.push_date as pushDate","brp.push_time as pushTime","br.report_name as reportName","bs.name as shopName")
                ->leftJoin("base_report as br","brp.report_id","=","br.id")
                ->leftJoin("base_shop as bs","brp.shop_id","=","bs.id")
                ->where("brp.user_id",$userId)
                ->where("brp.is_deleted",0)
                ->where("brp.is_push","1")
                ->where("br.is_deleted",0)
                ->get();
        return $rt;
    }
    
    /** 
      * 函数名: reportPushList
      * 用途: 根据用户ID、店铺ID查询报表推送列表
      *
      * @access public 
      * @param userId 用户Id
      * @return array 
    */ 
    public function reportPushListByShopId($userId,$shopId){
        $rt = DB::table("biz_report_push as brp")
                ->select("brp.report_id as reportId","brp.user_id as userId","brp.shop_id as shopId","brp.is_push as isPush","brp.push_date as pushDate","brp.push_time as pushTime","br.report_name as reportName","bs.name as shopName")
                ->leftJoin("base_report as br","brp.report_id","=","br.id")
                ->leftJoin("base_shop as bs","brp.shop_id","=","bs.id")
                ->where("brp.user_id",$userId)
                ->where("brp.shop_id",$shopId)
                ->where("brp.is_deleted",0)
                ->where("brp.is_push","1")
                ->where("br.is_deleted",0)
                ->get();
        return $rt;
    }
    
    
    /** 
      * 函数名: reportList
      * 用途: 报表推送列表
      *
      * @access public 
      * @param userId 用户Id
      * @return array 
    */ 
    public function reportList(){
        $rt = DB::table("base_report")
                ->select("id as reportId","report_name as reportName")
                ->where("is_deleted",0)
                ->get();
        return $rt;
    }
    
    /** 
      * 函数名: findResportByUSR
      * 用途: 根据用户ID，店铺ID，报表ID查看报表推送信息
      *
      * @access public 
      * @param reportId Int 报表ID
      * @param userId Int 用户ID
      * @param shopId Int 店铺ID
      * @param pushDate String 推送日期
      * @param pushTime Int 推送时间
      * @return array 
    */ 
    public function findResportByUSR($userId,$shopId,$reportId){
        $rt = DB::table("biz_report_push")
                ->where("user_id",$userId)
                ->where("shop_id",$shopId)
                ->where("report_id",$reportId)
                ->first();
        return $rt;
    }
    
    /** 
      * 函数名: insertReportByUSR
      * 用途: 新增报表推送设置
      *
      * @access public 
      * @param reportId Int 报表ID
      * @param userId Int 用户ID
      * @param shopId Int 店铺ID
      * @param pushDate String 推送日期
      * @param pushTime Int 推送时间
      * @return array 
    */ 
    public function insertReportByUSR($reportId,$userId,$shopId,$pushDate,$pushTime){
        $arr["id"] = get_newid("biz_report_push");
        $arr["is_deleted"] = 0;
        $arr["create_date"] = get_ms();
        $arr["report_id"] = $reportId;
        $arr["user_id"] = $userId;
        $arr["shop_id"] = $shopId;
        $arr["is_push"] = 1;
        $arr["push_date"] = $pushDate;
        $arr["push_time"] = $pushTime;
        $rt = DB::table('biz_report_push')->insert($arr);
        return $rt;
    }
    
    /** 
      * 函数名: updateReportByUSR
      * 用途: 根据用户ID，店铺ID，报表ID修改报表推送信息
      *
      * @access public 
      * @param reportId Int 报表ID
      * @param userId Int 用户ID
      * @param shopId Int 店铺ID
      * @param pushDate String 推送日期
      * @param pushTime Int 推送时间
      * @param isPush Int 是否推送
      * @return array 
    */ 
    public function updateReportByUSR($userId,$shopId,$reportId,$pushDate,$pushTime,$isPush){
        if($pushTime){
            $arr["push_date"] = $pushDate;
            $arr["push_time"] = $pushTime;
        }
        if($isPush != null){
            $arr["is_push"] = $isPush;
        }
        $rt = DB::table("biz_report_push")
                ->where("user_id",$userId)
                ->where("shop_id",$shopId)
                ->where("report_id",$reportId)
                ->update($arr);
        return $rt;
    }
}