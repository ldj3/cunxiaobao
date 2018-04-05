<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryImage extends Model {
    protected $table = 'base_image';
    public $timestamps = false;
        
    static function getImageListByBizId($bizId,$imgType){
        $imgList = DB::table("base_image")
                     ->where("is_deleted",0)
                     ->where("biz_id",$bizId)
                     ->where("type",$imgType)
                     ->orderBy("create_date","asc")
                     ->get();
        if($imgList){
            $imgList = obj2arr($imgList);
            return $imgList;
        }else{
            return false;
        }
    }
}