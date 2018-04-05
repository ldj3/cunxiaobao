<?php 
namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

class PayInfoModel extends Model {
    protected $table = 'pay_info';
    public $timestamps = false;
      

    //客户列表
    public static function payList(){
      $payList = DB::table("pay_info")
                  ->select("id","pay_name as payName")
                  ->get();
      return $payList;
    }

    public static function addPayInfo($payName,$remark){
            $data = array(
    			"customer_name"=>$customerName,
    			"customer_mobile"=>$customerMobile);
            
	    	DB::table("customer")->insert($data);
    }
}