<?php namespace App\Http\Controllers\Customer;
use App\Admin;
use Session;
use Redirect;
use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
class CustomerController extends BaseController {
    public function __construct()
    {
        $this->arr_title[] = "首页";
        $this->backstage_auth();
    }
    public function CustomerList()
    {
        //echo "<pre>";print_r(Session::all());die;
        //echo "<pre>";print_r(Cache::get('testC'));die;
        return view("Customer.CustomerList",["arr_title" => $this->arr_title]);
    }
}
