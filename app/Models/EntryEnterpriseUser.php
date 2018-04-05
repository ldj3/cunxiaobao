<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryEnterpriseUser extends Model {
    protected $table = 'base_enterprise_user';
    public $timestamps = false;    
      
    //登录验证
    static function loginCheck($mobile,$password){
        $password = md5(Config::get("app.login_key").$password);
        $user = DB::table("base_enterprise_user")
                  ->where("is_deleted",0)
                  ->where("mobile",$mobile)
                  ->where("password",$password)
                  ->first();
        return $user;
    }

    //获取用户信息
    public function getUserInfo($userId){
        $user = DB::table("base_enterprise_user as beu")
                  ->select('beu.id','beu.create_date','beu.modify_date','beu.creator','beu.editor','beu.version','beu.enterprise','beu.name','beu.mobile','beu.role','e.id as eid','e.name as ename')
                  ->join('base_enterprise as e', 'beu.enterprise', '=', 'e.id')
                  ->where("beu.is_deleted",0)
                  ->where("beu.id",$userId)
                  ->first();
        return $user;
    }
    
    //Enterprise获取所有用户
    static function usersList($enterpriseId,$page,$rows){
        $offset = ($page-1)*$rows;
        $users = DB::table("base_enterprise_user")
                   ->where("enterprise",$enterpriseId)
                   ->where("is_deleted",0)
                   ->skip($offset)
                   ->take($rows)
                   ->get();
        $users = obj2arr($users);
        //$users = if_null($users);
        return $users;
    }
    
    //获取用户第一个店铺
    static function getFirstShop($enterpriseId,$userId){
        $shop = DB::table('base_enterprise_user_permit')
                          ->where('enterprise',$enterpriseId)
                          ->where('enterprise_user',$userId)
                          ->orderBy('create_date')
                          ->first();
        return $shop->shop;
    }

    //获取用户
    public static function checkUserExist($mobile){
        $user = DB::table("base_enterprise_user")
                  ->where("is_deleted",0)
                  ->where("mobile",$mobile)
                  ->first();
        return $user;
    }

    public function addEnterpriseUser($enterpriseId,$contact,$mobile,$password){

      $enterpriseUserId = create_uuid();
      $time = get_ms();
      $password = md5("y8TWzAiCZ8yTZF6rJfYgNRiCfF8cHkgg".$password);
      $data = array(
        'id'=>$enterpriseUserId,
        'is_deleted'=>0,
        'create_date'=>$time,
        'modify_date'=>$time,
        'version'=>1,
        'enterprise'=>$enterpriseId,
        'name'=>$contact,
        'mobile'=>$mobile,
        'password'=>$password,
        'role'=>'boss');

      $count = DB::table('base_enterprise_user')->insert($data);
      return $enterpriseUserId;
    }

    public function updateEnterpriseUser($userId,$contact,$password,$loginUserName){
      $time = get_ms();
      $data = array(
        'modify_date'=>$time,
        'name'=>$contact,
        'editor'=>$loginUserName);
      //echo "string ".$password;
      if($password != null){

        $password = md5("y8TWzAiCZ8yTZF6rJfYgNRiCfF8cHkgg".$password);
        $data['password'] = $password;
        //print_r($data);
      }
      DB::table('base_enterprise_user')->where('id', $userId)->update($data);
      return $userId;
    }

    public function updateEnterpriseUserPassWord($userId,$password){
      $time = get_ms();
      $data = array(
        'modify_date'=>$time);
      if($password != null){
        $password = md5("y8TWzAiCZ8yTZF6rJfYgNRiCfF8cHkgg".$password);
        $data['password'] = $password;
      }
      DB::table('base_enterprise_user')->where('id', $userId)->update($data);
      return $userId;
    }
	
	//获取企业积分
	function getEnterpriseIntegral($enterpriseId){
		$integral = DB::table("base_enterprise")
					  ->where("id",$enterpriseId)
					  ->pluck("integral");
		return $integral;
	}
    
    //修改个人信息
    function updateEnterpriseUserInfo($arr){
        $rt = DB::table('base_enterprise_user')
                ->where("id",$arr["id"])
                ->update($arr);
        return $rt;
    }
}