<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryRole extends Model {
    protected $table = 'base_user_role';
    public $timestamps = false;
 
    //角色列表
    static function roleList($enterpriseId,$shop,$page,$rows){
        $offset = ($page-1)*$rows;
        $roles = DB::table("base_user_role")
                   ->where("enterprise",$enterpriseId)
                   ->where("is_deleted",0)
                   ->skip($offset)
                   ->take($rows)
                   ->get();
        $roles = obj2arr($roles);
        if(empty($roles)){
            return false;
        }else{
            return $roles;
        }
    }
    
    //新增角色
    static function addRole($role){
        if(empty($role)){
            $data = array("0" => false,"1" => "角色信息不可为空");
        }else{
            $isExit = DB::table("base_user_role")
                        ->where("enterprise",$role["enterprise"])
                        ->where("shop",$role["shop"])
                        ->where("role_name",$role["role_name"])
                        ->where("is_deleted",0)
                        ->count();

            if($isExit == 0){
                $rt = DB::table('base_user_role')->insert($role);
                if($rt){
                    $data = array("0" => true,"1" => "角色创建成功");
                }else{
                    $data = array("0" => false,"1" => "角色创建失败，请重新操作");
                }
            }else{
                $data = array("0" => false,"1" => "角色名称已存在");
            }
        }
        return $data;
    }
    
}