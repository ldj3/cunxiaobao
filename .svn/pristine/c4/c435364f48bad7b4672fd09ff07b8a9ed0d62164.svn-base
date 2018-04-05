<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EnterpriseUser extends Model {
    protected $table = 'base_enterprise_user';
    public $timestamps = false;
    public function get_first_user($enterprise_id)
    {
        $rs = DB::table("base_enterprise_user")
                ->select(array("id","name"))
                ->where("enterprise",$enterprise_id)
                ->where("is_deleted",0)
                ->orderBy("create_date","desc")
                ->first();
        return $rs;
    }
    
    //生成订单shortCode
    public function shortCode($enterpriseId,$deviceType,$deviceCode,$deviceVersion,$appVersion,$userId){
        if(empty($enterpriseId) || empty($deviceType) || empty($deviceCode) || empty($deviceVersion) || empty($appVersion) || empty($userId)){
            return array("status" => false,"msg" => "参数传入错误");
        }
        
        $userNum = DB::table('base_enterprise_user')
                     ->where('is_deleted',0)
                     ->where('id',$userId)
                     ->count();
        
        if($userNum == 0){
            return array("status" => false,"msg" => "userId不合法");
        }
        
        //查询企业设备信息表信息
        $enterpriseDevice = DB::table('base_enterprise_device')
                              ->where('enterprise_id',$enterpriseId)
                              ->where('device_code',$deviceCode)
                              ->where('status',1)
                              ->first();
        
        //判断企业设备信息表是否有记录
        if(empty($enterpriseDevice)){
            $data['enterprise_id'] = $enterpriseId;//企业id
            $data['device_type'] = $deviceType;//设备类型
            $data['device_code'] = $deviceCode;//设备唯一码
            $data['device_version'] = $deviceVersion;//系统版本号
            $data['app_version'] = $appVersion;//app版本号
            $data['create_date'] = strtotime(date('y-m-d h:i:s',time()))*1000;//创建时间
            $data['modify_date'] = strtotime(date('y-m-d h:i:s',time()))*1000;//修改时间
            $data['latest_date'] = strtotime(date('y-m-d h:i:s',time()))*1000;//最近访问时间
            //$data['expires_time'] = $expires_time;//过期时间
            $data['status'] = 1;//是否可用。1为可用，0为不可用
            
            //插入记录
            $enterpriseDevice['id'] = DB::table('base_enterprise_device')->insertGetId($data);
        }
        $enterpriseDevice = obj2arr($enterpriseDevice);

        //查询用户设备关系表信息
        $userDevice = DB::table('base_user_device')
                        ->where('user_id',$userId)
                        ->where('device_id',$enterpriseDevice['id'])
                        ->first();
        $userDevice = obj2arr($userDevice);

        if(empty($userDevice)){
            $userDeviceNum = DB::table('base_user_device')
                        ->where('user_id',$userId)
                        ->count();
            //计算返回码，若返回码属于0 - 10之间，则前面补零。
            $userDeviceNum = str_pad($userDeviceNum,2,"0",STR_PAD_LEFT);
            $arr['short_code'] = $userDeviceNum;
            $arr['user_id'] = $userId;//用户id
            $arr['device_id'] = $enterpriseDevice['id'];//企业设备信息表id
            
            //插入记录
            $rt = DB::table('base_user_device')->insert($arr);

            return array("status" => false,"msg" => "短码提取成功","shortCode" => $userDeviceNum);
        }
        return array("status" => false,"msg" => "短码提取成功","shortCode" => $userDevice['short_code']);
    }
}