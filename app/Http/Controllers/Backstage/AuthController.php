<?php namespace App\Http\Controllers\Backstage;
use Session;
use DB;
use Input;
use Config;
use App\EnterpriseUser;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Controllers\BaseController;
use App\Models\EntryToken;
use App\Models\EntryWarehouse;
use App\Models\EntryEnterpriseUserPermit;
use Cache;
use App\Models\EntryEnterpriseUser;

class AuthController extends BaseController {
	
    public function login()
    {
        return view("backstage.login");
    }
	public function captcha($tmp)
	{
		$builder = new CaptchaBuilder;
        $builder->build(300,80,null);//可以设置图片宽高及字体 $width = 100, $height = 40, $font = null
        $phrase = $builder->getPhrase();//获取验证码的内容
        Session::flash('milkcaptcha', $phrase);//把内容存入session
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
	}
    
    public function chk_auth()
    {
        $data = array();
        $name = trim(Input::get("name"));
        $password = md5(trim(Input::get("password")));
        $captcha = trim(Input::get("captcha"));
        if(!$name){
            $data = array("status" => 0,"msg" => "用户名不能为空!");
            return response()->json($data);
        }
        if(!$password){
            $data = array("status" => 0,"msg" => "密码不能为空!");
            return response()->json($data);
        }
        if(!$captcha){
            $data = array("status" => 0,"msg" => "验证码不能为空!");
            return response()->json($data);
        }
        if($captcha != Session::get("milkcaptcha")){
            $data = array("status" => 0,"msg" => "验证码错误!");
            return response()->json($data);
        }
        $user = EntryEnterpriseUser::loginCheck($name,$password);

        if($user){
            $entryToken = new EntryToken;
            $dbToken = $entryToken->findLastToken($user->id);
            //判断是否已登录
            if(Cache::get($dbToken)){
                $token = $dbToken;                
            }else{
                //生成token值
                $arr = $this->createToken($user->enterprise,$user->id);
                Cache::put($arr['token'], $arr['exp_date'], '525600');
                $token = $arr['token'];
            }
            $shopId = EntryEnterpriseUser::getFirstShop($user->enterprise,$user->id);
            $warehouseId = EntryWarehouse::warehouseIdList($user->enterprise,$shopId);
            $warehouseId = implode(",", $warehouseId);
            $entryEnterpriseUserPermit = new EntryEnterpriseUserPermit();
            $permit = $entryEnterpriseUserPermit->getUserPermit($shopId,$user->id);
            Session::set("uid",$user->id);
            Session::set("name",$user->name);
            Session::set("enterpriseId",$user->enterprise);
            Session::set("warehouseId",$warehouseId);
            Session::set("shopId",$shopId);
            Session::set("mobile",$user->mobile);
            
            Session::set("setterPermits",$permit->setter_permits);
            Session::set("salesPermits",$permit->sales_permits);
            Session::set("purchasePermits",$permit->purchase_permits);
            Session::set("warehousePermits",$permit->warehouse_permits);
            $data = array("status" => 1,"msg" => "登录成功","token" => $token);
            return response()->json($data);
        }else{
            $data = array("status" => 0,"msg" => "帐号或者密码错误!");
            return response()->json($data);
        }
    }
    public function logout()
    {
        session::clear();
        return redirect("backstage/login");
    }
    //发送验证码
    public function sandmsg()
    {
        $mobile = trim($_POST["mobile"]);
        if(!is_mobile_num($mobile))
        {
            $data = array("status" => 0,"msg" => "手机号码格式错误");
            return response()->json($data);
        }
        //检查手机号码是否存在
        $rs = DB::table("base_enterprise_user")->where("mobile",$mobile)->where("is_deleted",0)->count();
        if($rs != 1)
        {
            $data = array("status" => 0,"msg" => "该帐号不存在!");
            return response()->json($data);
        }
        else//重置密码
        {
            $verify = create_sequence(6);
            $data["mobile"] = $mobile;
            $data["content"] = "您正在申请重置密码，验证码为：".$verify." ，如非本人操作，请勿需理会。";
            $data["send_time"] = "";
            $sms = new Sms();
            $sms->send_msg($data);
            $date_now = get_ms();
            $rs = DB::table("pub_verify")->insert([
                    'mobile' => $mobile,
                    'create_time' => $date_now,
                    'verify' => $verify,
                    'token' => '',
                    'type' => '0'
                ]);
            $data = array("status" => 1,"msg" => "验证码发送成功，请留意您的信息");
            return response()->json($data);
        }
    }
    //密码重置
    public function forgotpwd()
    {
        $mobile = trim($_POST["mobile"]);
        $password = trim($_POST["pwd"]);
        $verify = trim($_POST["verify"]);
        if(!is_mobile_num($mobile))
        {
            $data = array("status" => 0,"msg" => "手机号码格式错误");
            return response()->json($data);
        }
        if(!$password)
        {
            $data = array("status" => 0,"msg" => "密码不可为空");
            return response()->json($data);
        }
        if(!$verify)
        {
            $data = array("status" => 0,"msg" => "验证码不可为空");
            return response()->json($data);
        }
        else
        {
            $pub_verify = DB::table("pub_verify")
                            ->where("mobile",$mobile)
                            ->orderBy('create_time','desc')
                            ->get();
            $pub_verify = obj2arr($pub_verify);
            if($pub_verify[0]['create_time'] < (time()-600)*1000)
            {
                $data = array("status" => 0,"msg" => "验证码已过期");
                return response()->json($data);
            }
            if($verify != $pub_verify[0]['verify'])
            {
                $data = array("status" => 0,"msg" => "验证码错误");
                return response()->json($data);
            }
        }
        //检查手机号码是否存在
        $rs = DB::table("base_enterprise_user")->where("mobile",$mobile)->where("is_deleted",0)->count();
        if($rs != 1)
        {
            $data = array("status" => 0,"msg" => "该帐号不存在!");
            return response()->json($data);
        }
        else//重置密码
        {
            $new_md5_password = md5($password);
            $rs = DB::table("base_enterprise_user")
                    ->where("mobile",$mobile)
                    ->where("is_deleted",0)
                    ->update(array("password"=>$new_md5_password));
            if($rs)
            {
                $data = array("status" => 1,"msg" => "密码重置成功,请重新登录!");
                return response()->json($data);
            }
            else
                $data = array("status" => 0,"msg" => "密码重置失败，请重新设置!");
                return response()->json($data);            
        }
    }
    public function resets_pwd()
    {
        $this->special_auth();
        if(Input::get("is_set"))//开始判断修改
        {
            $error = "";
            $old_pwd = trim(Input::get("old_pwd"));
            $new_pwd = trim(Input::get("new_pwd"));
            if(!$old_pwd) $error = "旧密码不能为空!";
            if(!$new_pwd) $error = "新密码不能为空!";
            if(mb_strlen($new_pwd) < 6) $error = "新密码长度低于6位!";
            if($old_pwd == $new_pwd) $error = "新密码与旧密码一致!";
            $user_id = Session::get("uid");
            $obj_user = new EnterpriseUser();
            $rs_user = $obj_user->find($user_id);
            if($rs_user->password != md5($old_pwd)) $error = "输入的旧密码不正确!";
            if($error)
            {
                $data = array("status" => 0,"msg" => $error);
                return response()->json($data);
            }
            //开始修改
            $user = EnterpriseUser::find($user_id);
            $user->password = md5($new_pwd);
            $user->save();
            $data = array("status" => 1,"msg" => "密码修改成功,下次登录生效");
            return response()->json($data);
        }
        else
        {
            $arr_title[] = "其他设置";
            $arr_title[] = "修改密码";
            return view("backstage.resetpwd",["arr_title" => $arr_title]);
        }
    }

   
}