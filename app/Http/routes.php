<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin', function () {
    return view('userlogin');
});
Route::group(['namespace' => 'Backstage'], function () {
    Route::get('backstage/captcha/{tmp}', 'AuthController@captcha');//验证码
    Route::get('backstage/login', 'AuthController@login');//登录检测
    Route::get('backstage/logout', 'AuthController@logout');//退出
    Route::post('backstage/chk_auth', 'AuthController@chk_auth');//登录检测
    Route::post('backstage/register_pwd', 'AuthController@register_pwd');//注册检测
    Route::post('backstage/sendmsg', 'AuthController@sandmsg');//发送验证码
    
    Route::get('backstage/index', 'IndexController@index');
    
    Route::get('backstage/goods', 'GoodsController@index');
    Route::post('backstage/goods/search', 'GoodsController@search');//商品列表数据请求查找
    Route::post('backstage/goods/getShopProperty', 'GoodsController@getShopProperty');//获取单位列表以及商品三大属性
    Route::post('backstage/goods/addGoods', 'GoodsController@addGoods');//增加商品
    Route::post('backstage/goods/del', 'GoodsController@del');//删除商品
    Route::post('backstage/goods/getTag', 'GoodsUnitController@getTag');//获取二维属性
    Route::post('backstage/goods/addTagGroup', 'GoodsController@addTagGroup');//增加商品标签组
    Route::post('backstage/goods/delTagGroup', 'GoodsController@delTagGroup');//删除商品标签组
    Route::post('backstage/goods/addTagSon', 'GoodsController@addTagSon');//增加标签分组子项
    
    Route::post('backstage/unit/unitList', 'GoodsUnitController@unitList');
    
    Route::any('backstage/shop/getShopAttribute', 'ShopController@getAttribute');//获取店铺属性

    Route::get('backstage/goodsListIndex', function () {
        return view('backstage.goodslist');
    });
});

Route::group(['namespace' => 'OrderList'], function () {
    Route::get('Order/SaleOrderList', 'SaleOrderController@SaleOrderList');//订单管理
    Route::get('Order/PurchaseOrderList', 'PurchaseOrderController@PurchaseOrderList');
    Route::get('Order/ReturnSaleOrderList', 'ReturnSaleOrderController@ReturnSaleOrderList');
    Route::get('Order/ReturnPurchaseOrderList', 'ReturnPurchaseOrderController@ReturnPurchaseOrderList');
    Route::get('Order/CheckOrderList', 'CheckOrderController@CheckOrderList');
    Route::get('Order/WarehouseAllocationOrderList', 'WarehouseAllocationOrderController@WarehouseAllocationOrderList');
    Route::get('Order/OutOrderList', 'OutOrderController@OutOrderList');
    Route::get('Order/InOrderList', 'InOrderController@InOrderList');
    Route::get('Order/ReceivablesList', 'ReceivablesController@ReceivablesList');
});

Route::group(['namespace' => 'Customer'], function () {
    Route::get('Customer/CustomerList', 'CustomerController@CustomerList');//客户
    Route::get('Customer/SupplierList', 'SupplierController@SupplierList');
    Route::get('Customer/CustomerGroupList', 'CustomerGroupController@CustomerGroupList');
    Route::get('Customer/SupplierGroupList', 'SupplierGroupController@SupplierGroupList');
});

Route::group(['namespace' => 'Setter'], function () {
    Route::get('Setter/ShopSetterList', 'ShopSetterController@ShopSetterList');//基础设置
    Route::get('Setter/WarehouseSetterList', 'WarehouseSetterController@WarehouseSetterList');
    Route::get('Setter/UpdatePassWord', 'UserInfoController@UpdatePassWord');
    Route::get('Setter/ReportSetter', 'ReportSetterController@ReportSetter');
    Route::get('Setter/PrintSetter', 'PrintSetterController@PrintSetter');
});