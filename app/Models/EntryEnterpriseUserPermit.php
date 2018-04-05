<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryEnterpriseUserPermit extends Model {
    protected $table = 'base_enterprise_user_permit';
    public $timestamps = false;

    public function addEnterpriseUserPermit($enterpriseId,$enterpriseUserId,$shopId,$roleId){

      $enterpriseUserPermitId = create_uuid();
      $time = get_ms();

      $data = array(
        'id'=>$enterpriseUserPermitId,
        'is_deleted'=>0,
        'create_date'=>$time,
        'modify_date'=>$time,
        'version'=>1,
        'enterprise'=>$enterpriseId,
        'enterprise_user'=>$enterpriseUserId,
        'shop'=>$shopId,
        'permits'=>0,
        'role_id'=>$roleId);

      $count = DB::table('base_enterprise_user_permit')->insert($data);
      return $enterpriseUserPermitId;
    }

    public function updateEnterpriseUserPermit($enterpriseId,$enterpriseUserId,$shopId,$roleId,$loginUserName){
      $time = get_ms();

      $data = array(
        'modify_date'=>$time,
        'editor'=>$loginUserName,
        'role_id'=>$roleId);
      DB::table('base_enterprise_user_permit')
          ->where('enterprise', $enterpriseId)
          ->where('enterprise_user', $enterpriseUserId)
          ->where('shop', $shopId)
          ->update($data);
      return true;
    }

    public function getUserPermit($shopId,$userId){
      $newPermits = DB::table('base_user_role as ur')
                ->leftJoin('base_enterprise_user_permit as eup','ur.id','=','eup.role_id')
                ->select('ur.setter_permits','ur.sales_permits','ur.purchase_permits','ur.warehouse_permits')
                ->where('eup.enterprise_user',$userId)
                ->where('eup.shop',$shopId)
                ->first();
                return $newPermits;
    }
}