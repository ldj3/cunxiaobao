<?php namespace App\Http\Controllers\Setter;
use App\Admin;
use Session;
use Redirect;
use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
class UserInfoController extends BaseController {
    public function __construct()
    {
        $this->arr_title[] = "首页";
        $this->backstage_auth();
    }
    public function UpdatePassWord()
    {
        //echo "<pre>";print_r(Session::all());die;
        //echo "<pre>";print_r(Cache::get('testC'));die;
        return view("Setter.UpdatePassWord",["arr_title" => $this->arr_title]);
    }
}
