<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Sync\SyncInfoHelper;

class EntryVerify extends Model {
    protected $table = 'pub_verify';
    public $timestamps = false;    
    //检查是否过期
    public static function checkCodeExpire($mobile){
        $verify = DB::table("pub_verify")
                  ->where("mobile",$mobile)
                  ->orderBy('create_time', 'desc')
                  ->first();
        if($verify == null){
            return false;
        }
        $time = SyncInfoHelper::getMillisecond();
        $secs = ($time - $verify->create_time) / 1000;
        if($secs > 60 * 10 * 60){//10分钟过期
          return false;
        }
        return true;
    }

    //检查Code是否正确
    public static function checkCode($mobile,$code,$type=1){
        $verify = DB::table("pub_verify")
                  ->where("mobile",$mobile)
                  ->where("verify",$code)
                  ->where("type",$type)
                  ->first();
        if($verify == null){
          return false;
        }
        return true;
    }
    
    /** 
      * addVerifyCode
      * 检查验证码是否过期--2分钟
      *
      * @access public 
      * @param $mobile 手机号码 
      * @return Booleans 
    */ 
    public function checkCodeExpireTwoMins($mobile){
        $verify = DB::table("pub_verify")
                    ->select("create_time as createTime")
                    ->where("mobile",$mobile)
                    ->orderBy('create_time', 'desc')
                    ->first();
        if(!$verify){
            return false;
        }
        $time = get_ms();

        $secs = ($time - $verify->createTime) / 1000;
        if($secs > 60 * 2 * 60){//2分钟过期
          return false;
        }
        return true;
    }
    
    /** 
      * addVerifyCode
      * 新增验证码
      *
      * @param mixed $mobile 手机号码 
      * @param mixed $code 验证码 
      * @param mixed $token token值
      * @param mixed $type 短信类型 0 注册验证码 1 微信绑定验证码 2 消费者微信绑定验证码
      * @return int 
    */ 
    public function addVerifyCode($mobile,$code,$token,$type){
        $arr["mobile"] = $mobile;
        $arr["create_time"] = get_ms();
        $arr["verify"] = $code;
        $arr["token"] = $token;
        $arr["type"] = $type;
        $rt = DB::table('pub_verify')->insert($arr);
        return $rt;
    }
}