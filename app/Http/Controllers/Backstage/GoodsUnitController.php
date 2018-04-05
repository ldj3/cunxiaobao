<?php 
namespace App\Http\Controllers\Backstage;
use Session;
use DB;
use Input;
use Storage;
use Config;
use Request;
use App\Models\EntryUnit;
use App\Http\Controllers\BaseController;


class GoodsUnitController extends BaseController {
    
    public function __construct(){
        $this->backstage_auth();
    }
    
    //获取单位列表
    public function unitList(){
        $showGoodsPermit = $this->showGoodsPermit();
        if(!$showGoodsPermit){
            $data = array("status" => 0,"msg" => "暂无权限");
            return response()->json($data);
        }
        $unitGroupId = trim(Input::get("unitGroupId"));
        if($unitGroupId){
            $entryUnit = new EntryUnit();
            $unitList = $entryUnit->unitGroupList($unitGroupId);
            if(empty($unitList)){
                $data = array("status" => 0,"msg" => "暂无数据");
            }else{
                $data = array("status" => 1,"msg" => "数据请求成功","unitList" => $unitList);
            }
        }else{
            $data = array("status" => 0,"msg" => "分组ID不可为空");
        }
        return response()->json($data);
    }
    
    //获取二维标签
    public function getTag(){
        $enterpriseId = Session::get("enterpriseId");
        $shopId = Session::get("shopId");
        $goodsTag = DB::table("biz_goods_tag")
                       ->select("id","tag_name","idtype")
                       ->where("enterprise",$enterpriseId)
                       ->where("shop",$shopId)
                       ->where("is_deleted",0)
                       ->where("parent_id","")
                       ->get();
        $goodsTag = obj2arr($goodsTag);
        if(count($goodsTag)>0){
            for($i = 0; $i < count($goodsTag);$i++){
                $goodsTagSon = DB::table("biz_goods_tag")
                                 ->select("id","tag_name")
                                 ->where("is_deleted",0)
                                 ->where("parent_id",$goodsTag[$i]["id"])
                                 ->get();
                $goodsTagSon = obj2arr($goodsTagSon);
                $goodsTag[$i]["group"] = $goodsTagSon;
            }
            $data = array("status" => 1,"goodsTag" => $goodsTag);
        }else{
            $data = array("status" => 0,"msg" => "暂无数据");
        }
        return response()->json($data);
    }
}
