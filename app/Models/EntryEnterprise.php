<?php namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Sync\SyncInfoHelper;
use App\Models\EntryShop;
use App\Models\AppUserRole;

class EntryEnterprise extends Model {
    protected $table = 'base_enterprise';
    public $timestamps = false;    
    
    public function addEnterprise($phone,$contact,$mobile,$industry,$address,$description,$password,$shopname){
      
      DB::beginTransaction();
      try{

        $enterpriseId = EntryEnterprise::addEnterpriseInfo($mobile,$shopname);
        //echo "enterpriseId = ".$enterpriseId;
        if($enterpriseId != null){
          $shopId = EntryShop::addShopInfo($enterpriseId,$shopname,$contact,$phone,$mobile,$industry,$address,$description);
          //echo " shopId = ".$shopId;
          if($shopId != null){
            $enterpriseUserId = EntryEnterpriseUser::addEnterpriseUser($enterpriseId,$contact,$mobile,$password);
            //echo " enterpriseUserId = ".$enterpriseUserId;
            if($enterpriseUserId != null){
              $appUserRole = new AppUserRole;
              $bossId = $appUserRole->addBossRole($enterpriseId,$shopId,$contact);
              $appUserRole->addSalesRole($enterpriseId,$shopId,$contact);
              $appUserRole->addPurchaseRole($enterpriseId,$shopId,$contact);
              $appUserRole->addWarehouseRole($enterpriseId,$shopId,$contact);

              $enterpriseUserPermitId = EntryEnterpriseUserPermit::addEnterpriseUserPermit($enterpriseId,$enterpriseUserId,$shopId,$bossId);
              //echo " enterpriseUserPermitId = ".$enterpriseUserPermitId;
              if($enterpriseUserPermitId != null){
                $industryInfo = EntryIndustry::getIndustry($industry);
                //print_r($industryInfo);
                $attributeId = EntryAttribute::addAttribute($shopId,$industryInfo);
                //echo " attributeId ".$attributeId;
                $appEnterpriseWarehouseInfo = new AppEnterpriseWarehouseInfo;
                $appEnterpriseWarehouseInfo->addDefWarehouse($enterpriseId);
                DB::commit();
                return true;
              }
            }
          }
        }
      }catch(Exception $ex){

      }
      DB::rollback();
      return false;
    }

    public static function addEnterpriseInfo($mobile,$shopname){
      $enterpriseId = create_uuid();
      $time = get_ms();

      $data = array(
        'id'=>$enterpriseId,
        'is_deleted'=>0,
        'create_date'=>$time,
        'modify_date'=>$time,
        'version'=>1,
        'code'=>'@'.$mobile,
        'name'=>$shopname,
        'mobile'=>$mobile,
        'is_finish_register'=>2);

      $count = DB::table('base_enterprise')->insert($data);

      return $enterpriseId;
    }

    //获取该企业下用户的所有数据
    public function getEnterpriseInfo($enterpriseUser,$enterpriseId,$userId,$deviceType,$deviceId,$deviceVersion,$appVersion,$isShortCode){
      $enterpriseInfo = AppEnterpriseInfo::getEnterpriseInfo($enterpriseId);
      $shopList = AppShopInfo::getUserShopInfo($enterpriseId,$userId);

      for ($index=0; $index < sizeof($shopList); $index++) { 
        //通过店铺ID,用户ID.获取用户拥有的仓库
        $shopId = $shopList[$index]->id;
        $warehouseList = EntryWarehouse::getUserWarehouse($enterpriseId,$shopId,$userId);
        $shopList[$index]->warehouse = $warehouseList;
        
        $newPermits = EntryEnterpriseUserPermit::getUserPermit($shopId,$userId);
        //print_r($newPermits);
        $arrtibute = AppAttributeInfo::getAttributeInfo($shopId);
        //打印信息
        $deviceInfo = AppDeviceInfo::getBluetoothDeviceInfo($userId,$shopId);
        $shopPrintInfo = AppShopInfo::getShopBluetoothInfo($shopId);

        $bluetoothData = array('shopName'=>$shopPrintInfo->shop_name,
              'address'=>$shopPrintInfo->address,
              'qq'=>$shopPrintInfo->qq,
              'fax'=>$shopPrintInfo->fax,
              'mobile'=>$shopPrintInfo->mobile,
              'remark'=>$shopPrintInfo->remark,
              'bluetoothMac'=>$deviceInfo->bluetooth_mac,
              'bluetoothName'=>$deviceInfo->bluetooth_name,
              'printerName'=>$deviceInfo->printer_name,
              'templetsId'=>$deviceInfo->templets_id,
              'templateGenmethod' =>$deviceInfo->template_genmethod);
        $shopList[$index]->bluetoothData = $bluetoothData;

        $arr = array('priceScale'=>$arrtibute->price_scale,
              'qtyScale'=>$arrtibute->qty_scale,
              'moneyScale'=>$arrtibute->money_scale,
              'orderLowWholeDiscount'=>$arrtibute->order_low_discount,
              'orderDiscountMemory'=>$arrtibute->order_discount_memory,
              'orderQtyMemory'=>$arrtibute->order_qty_memory,
              'goodsPropertyOne'=>$arrtibute->goods_property_one,
              'goodsPropertyTwo'=>$arrtibute->goods_property_two,
              'goodsPropertyThree'=>$arrtibute->goods_property_three,
              'dataDateRange'=>$arrtibute->date_range,
              'orderSettletype'=>$arrtibute->order_settle_type,
              'arrtibuteId'=>$arrtibute->id,
              'barcodeRule'=>$arrtibute->barcode_rule,
              'shopType'=>$arrtibute->shop_type);
        $shopList[$index]->arrtibute = $arr;
        $shopList[$index]->permits=$permits;
        $shopList[$index]->setterPermits = $newPermits == null ? 0 :$newPermits->setter_permits;
        $shopList[$index]->salesPermits = $newPermits == null ? 0 :$newPermits->sales_permits;
        $shopList[$index]->purchasePermits = $newPermits == null ? 0 :$newPermits->purchase_permits;
        $shopList[$index]->warehousePermits = $newPermits == null ? 0 :$newPermits->warehouse_permits;
      }
      $shortCodeValue = "";
      if($isShortCode){
        $shortCode = EnterpriseUser::shortCode($enterpriseId,$deviceType,$deviceId,$deviceVersion,$appVersion,$userId);
        $shortCodeValue = $shortCode['shortCode'];
      }
      
      $warehouseList = EntryWarehouse::getWarehouseList($enterpriseId);

      $data = array('enterprise'=>$enterpriseId,
              'name'=>$enterpriseUser->name,
              'mobile'=>$enterpriseUser->mobile,
              'id'=>$enterpriseUser->id,//用户ID(经手人Id)
              'shortCode'=>$shortCodeValue,
              'warehouseList'=>$warehouseList,

              'shops'=>$shopList);
      return $data;
    }
    
    //判断是否4.0版本
    public function isFinishRegister($enterpriseId){
        $isFinishRegister = DB::table("base_enterprise")
                              ->where("id",$enterpriseId)
                              ->pluck("is_finish_register");
        return $isFinishRegister;
    }
    
    /** 
      * 函数名: getLimitDebtMoneyByEnterpriseId
      * 用途: 获取企业客户欠款限制额数
      *
      * @access public 
      * @param enterpriseId 企业Id
      * @return number 欠款限制额数
    */ 
    public function getLimitDebtMoneyByEnterpriseId($enterpriseId){
        $rt = DB::table("base_enterprise")
                ->where("id",$enterpriseId)
                ->pluck("limit_debt_money");
        $rt = doubleval($rt);
        return $rt;
    }
    
    
    /** 
      * 函数名: updateEnterpriseLimitDebtMoney
      * 用途: 获取企业客户欠款限制额数
      *
      * @access public 
      * @param enterpriseId 企业Id
      * @param limitDebtMoney 欠款限制额数
      * @return Int
    */ 
    public function updateEnterpriseLimitDebtMoney($enterpriseId,$limitDebtMoney){
        $arr["limit_debt_money"] = $limitDebtMoney;
        $arr["version"] = DB::table("base_enterprise")
                            ->where("id",$enterpriseId)
                            ->pluck("version");
        $arr["version"] += 1;
        $arr["modify_date"] = get_ms();
        $rt = DB::table("base_enterprise")
                ->where("id",$enterpriseId)
                ->update($arr);
        return $rt;
    }
}