<?php 
namespace App\Http\Controllers\Backstage;

use App\Models\EntryShop;
use Session;
use Input;
use App\Http\Controllers\BaseController;

class ShopController extends BaseController {
    protected $arr_shop = array();
    protected $price_scale = 2;
    protected $money_scale = 2;
    protected $qty_scale = 0;
    protected $industry = array();
    protected $shop_type = array();
    
    public function __construct(){
        
    }
    
    public function getAttribute(){
        $showShopPermit = $this->showShopPermit();

        if(!$showShopPermit){
            $data = array("success" => 0,"msg" => "暂无权限");
            return response()->json($data);
        }
        $entryShop = new EntryShop();
        $shopId = Session::get("shopId");
        $rt = $entryShop->getShopThreeAtt($shopId);
        
        $data = array("success" => 1,"msg" => "数据请求成功","data" => $rt);
        return response()->json($data);
    }

}
