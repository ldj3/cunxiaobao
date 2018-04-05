<?php 
namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class User extends Model {
    protected $table = 'base_user';
    public $timestamps = false;
      
    //登录验证
    public function loginCheck($mobile,$password){
        $password = md5(Config::get("app.loginKey").$password);
        $user = DB::table("base_user")
                  ->select("id as userId","user_name as userName","mobile as mobile")
                  ->where("is_deleted",0)
                  ->where("mobile",$mobile)
                  ->where("password",$password)
                  ->first();
        return $user;
    }

    //账户列表
    public static function userList(){
      
      $userList = DB::table("base_user")
                  ->select("id as userId","user_name as userName","mobile as mobile")
                  ->where("is_deleted",0)
                  ->get();
      return $userList;

      
    }

}