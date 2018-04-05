<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryWechatBand extends Model {
    protected $table = 'biz_wechat_band';
    public $timestamps = false;
        
    public function getWechatUserList($userId){
        $rt = DB::table("biz_wechat_band as wb")
                ->select("wb.user_id as userId","wb.wechat_user_id as wechatUserId","wb.create_date as createDate","wu.nickname","wu.headimgurl","wu.modify_time as modifyTime")
                ->join("base_wechat_user as wu","wb.wechat_user_id","=","wu.id")
                ->where("wb.user_id",$userId)
                ->get();
        return $rt;
    }
}