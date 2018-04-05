<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;


class EntryInoutDtl extends Model {
	protected $table = 'biz_inout_dtl';
    public $timestamps = false;

    public function saveInoutDtl($enterpriseId,$shopId,$createName,$maxVersion = 0,$goods,$dimensionalId,$warehouseRelationId,$mode,$source,$bill_no,$remark,$warehouseId,$goodsUnitId,$unit,$unitSort,$batch_id,$qty){
    	$id = create_uuid();
        $time = get_ms();

        if($maxVersion == 0){
            $maxVersion = max_version('biz_inout_dtl',$enterpriseId)+1;
        }
    	$data = array(
    		'id'=> $id,
			'is_deleted'=> 0,
			'create_date'=> $time,
			'modify_date'=> $time,
			'creator'=> $createName,
			'editor'=> $createName,
			'version'=> $maxVersion,
			'enterprise'=> $enterpriseId,
			'shop'=> $shopId,
			'goods'=> $goods,
			'dimensional_id'=> $dimensionalId,
			'warehouse_relation_id'=> $warehouseRelationId,
			'mode'=> $mode,
			'qty'=> $qty,
			'source'=> $source,
			'bill_no'=> $bill_no,
			'cost_price'=> 0,
			'purchase_price'=> 0,
			'selling_price'=> 0,
			'remark'=> $remark,
			'warehouse_id'=> $warehouseId,
			'goods_unit_id'=> $goodsUnitId,
			'unit'=> $unit,
			'batch_id'=> $batch_id,
			'unit_sort'=> $unitSort
    		);
		DB::table('biz_inout_dtl')->insert($data);
    	return $id;
    }

		// /**
  //        * 期初
  //        */
  //       stockBegin,

  //       /**
  //        * 销售
  //        */
  //       sale,

  //       /**
  //        * 采购
  //        */
  //       purchase,

  //       /**
  //        * 简入库
  //        */
  //       simpleIn,

  //       /**
  //        * 改库存
  //        */
  //       modifyStock,

  //       /**
  //        * 改销售
  //        */
  //       modifySale,

  //       /**
  //        * 删销售
  //        */
  //       deleteSale,

  //       /**
  //        * 改采购
  //        */
  //       modifyPurchase,

  //       /**
  //        * 删采购
  //        */
  //       deletePurchase,

  //       /**
  //        * 调入
  //        */
  //       moveIn,

  //       *
  //        * 调出
         
  //       moveOut,

  //       /**
  //        * 盘点
  //        */
  //       takeStock,

  //       /**
  //        * 盘盈
  //        */
  //       inventoryProfit,

  //       /**
  //        * 盘亏
  //        */
  //       inventoryLoss,
  //       /**
  //        * 退货
  //        */
  //       returnStock,
  //       /**
  //        * 删退货
  //        */
  //       deletereturnStock,
  //       /**
  //        * 退采购
  //        */
  //       returnPurchase,
  //       /**
  //        * 删退采购
  //        */
  //       deleteReturnPurchase,
  //       /**
  //        * 入库
  //        */
  //       inStockPurchase,
  //       /**
  //        *出库
  //        */
  //       outStockPurchase,
}