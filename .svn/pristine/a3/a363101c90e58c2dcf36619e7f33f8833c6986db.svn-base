<?php namespace App\Http\Controllers\OrderList;
use App\Admin;
use Session;
use Redirect;
use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
class SaleOrderController extends BaseController {
    public function __construct()
    {
        $this->arr_title[] = "首页";
        $this->backstage_auth();
    }
    public function SaleOrderList()
    {
        //echo "<pre>";print_r(Session::all());die;
        //echo "<pre>";print_r(Cache::get('testC'));die;
        return view("Order.SaleOrderList");
    }
}
