<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

class EntryWarehouseRelation extends Model {
    protected $table = 'biz_warehouse_relation';
    public $timestamps = false;
    
    //保存商品仓库库存信息表
    public function saveWarehouseRelation($enterpriseId,$shopId,$createName,$maxVersion = 0,$inoutDtlMaxVersion,$warehouseId,$goods,$dimensionalId,$goodsUnitId,$unit,$unitSort,$qty=0,$source,$bill_no,$remark,$batch_id,$mode){
        
        $data = EntryWarehouseRelation::getWarehouseRelation($enterpriseId,$warehouseId,$goods,$dimensionalId,$goodsUnitId);
        //print_r($data);
        $warehouseRelationId;
        if($data == null){
            $warehouseRelationId = EntryWarehouseRelation::addWarehouseRelation($enterpriseId,$shopId,$createName,$maxVersion,$warehouseId,$goods,$dimensionalId,$goodsUnitId,$unit,$unitSort,$qty);
        }else{
            if($qty > 0){
                EntryWarehouseRelation::purchaseWarehouseRelation($maxVersion,$qty,$data->id,$createName);
            }else if($qty < 0){
                EntryWarehouseRelation::saleWarehouseRelation($maxVersion,$qty,$data->id,$createName);
            }
            $warehouseRelationId = $data->id;
        }

        EntryInoutDtl::saveInoutDtl($enterpriseId,$shopId,$createName,$inoutDtlMaxVersion,$goods,$dimensionalId,$warehouseRelationId,$mode,$source,$bill_no,$remark,$warehouseId,$goodsUnitId,$unit,$unitSort,$batch_id,$qty);
    }

    public function addWarehouseRelation($enterpriseId,$shopId,$createName,$maxVersion = 0,$warehouseId,$goods,$dimensionalId,$goodsUnitId,$unit,$unitSort,$qty=0){
        $id = create_uuid();
        $time = get_ms();

        if($maxVersion == 0){
            $maxVersion = max_version('biz_warehouse_relation',$enterpriseId)+1;
        }

        $data = array(
            'id'=> $id,
            'is_deleted'=> 0,
            'create_date'=> $time,
            'modify_date'=> $time,
            'enterprise'=> $enterpriseId,
            'shop'=> $shopId,
            'creator'=> $createName,
            'editor'=> $createName,
            'version'=> $maxVersion,
            'warehouse_id'=> $warehouseId,
            'goods'=> $goods,
            'dimensional_goods_id'=> $dimensionalId,
            'goods_unit_id'=> $goodsUnitId,
            'unit'=> $unit,
            'unit_sort'=> $unitSort,
            'qty'=> $qty
        );
        //print_r($data);
        DB::table('biz_warehouse_relation')->insert($data);
        return $id;
    }

    public function saleWarehouseRelation($maxVersion,$qty,$id,$editorName){
        $sql = "update biz_warehouse_relation set editor = ?,version = ?,qty = qty + ? where id = ? ";
        $count = DB::update($sql,array($editorName,$maxVersion,-abs($qty),$id));
        return $count > 0;
    }

    public function purchaseWarehouseRelation($maxVersion,$qty,$id,$editorName){
        $sql = "update biz_warehouse_relation set editor = ?,version = ?,qty = qty + ? where id = ? ";
        $count = DB::update($sql,array($editorName,$maxVersion,abs($qty),$id));
    }

    //获取商品仓库库存信息
    public function getWarehouseRelation($enterpriseId,$warehouseId,$goods,$dimensionalId,$goodsUnitId){

        $data = DB::table('biz_warehouse_relation')
                    ->where('enterprise',$enterpriseId)
                    ->where('goods',$goods)
                    ->where('dimensional_goods_id',$dimensionalId)
                    ->where('warehouse_id',$warehouseId)
                    ->where('goods_unit_id',$goodsUnitId)
                    ->first();
        return $data;
    }
}