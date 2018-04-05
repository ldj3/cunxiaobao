<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryUserAttr extends Model {
    protected $table = 'biz_user_attr';
    public $timestamps = false;
        
    /** 
      * 函数名: getUserById
      * 用途: 根据用户ID获取推送信息
      * @access public 
      * @param userId 用户Id
      * @return array 
    */ 
    public function getUserById($userId){
        $rt = DB::table("biz_user_attr")
                  ->where("idtype","en_user")
                  ->where("id",$userId)
                  ->first();
        return $rt;
    }
}