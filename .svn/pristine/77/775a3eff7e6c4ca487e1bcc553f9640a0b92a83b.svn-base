<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryGoodsBetchopLog extends Model {
    protected $table = 'biz_betchop_log';
    public $timestamps = false;
 
    //新增记录
    public function addLog($arr){
        $rt = array();
        $rt = DB::table("biz_betchop_log")
                ->insert($arr);
        return $rt;
    }
    
    //记录列表
    public function logsList($shopId,$rows,$offset){
        
        $query = DB::table("biz_betchop_log as bbl")
                   ->join("base_shop as bs","bbl.shop_id","=","bs.id")
                   ->select("bbl.id","bbl.content","bbl.create_date as createDate","bbl.username as userName","bbl.old_file as oldFile","bbl.is_deleted as isDeleted","bbl.modify_date as modifyDate","bs.name as shopName")
                   ->where("bbl.shop_id",$shopId);
                   
        $total = $query->count();
        $logsList = $query->orderBy("bbl.create_date","desc")
                       ->skip($offset)
                       ->take($rows)
                       ->get();
        $logsList = obj2arr($logsList);
        for($i = 0;$i < count($logsList); $i++){
            $logsList[$i]["createDate"] = date("Y-m-d H:i:s",$logsList[$i]["createDate"]/1000);
            if($logsList[$i]["modifyDate"]){
                $logsList[$i]["modifyDate"] = date("Y-m-d H:i:s",$logsList[$i]["modifyDate"]/1000);
            }else{
                $logsList[$i]["modifyDate"] = "";
            }
        }
        $data["logsList"] = $logsList;
        $data["total"] = $total;
        return $data;
    }
    
    //撤销批量记录
    public function delLogs($goodsBetchAddId){
        $rt = DB::table("biz_betchop_log")
                ->where("id",$goodsBetchAddId)
                ->update(["is_deleted" => 1,"modify_date" => get_ms()]);
        return $rt;
    }
    
}