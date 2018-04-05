<?php namespace App\Http\Controllers\Api;

class PermitHelper{
	//老板
	public static function getBoss(){
		return PermitHelper::getAllPermits();
	}
	//店长
	public static function getShopOwner(){
		$tag = '1,64,128,256,512,1024,2048,8,16,32';
		return PermitHelper::permitInfo($tag);
	}
	//店员
	public static function getSalesman(){
		$tag = '1,2048,128,256,512';
		return PermitHelper::permitInfo($tag);
	}
	//仓库管理员
	public static function getWarKeeper(){
		$tag = '1,256,1024,2048';
		return PermitHelper::permitInfo($tag);
	}

	public static function AllPermits(){
		$permit =
			0x8//PERMIT_RECEIPT
			| 0x10//PERMIT_WAREHOUSE
			| 0x20//PERMIT_VIEW_COST_PRICE
			| 0x40//PERMIT_UPDATE_ALL_ORDERS
			| 0x80//PERMIT_UPDATE_DELETE_ORDERS
			| 0x100//PERMIT_ADD_GOODS
			| 0x200//PERMIT_VIEW_ALL_CUSTOMERS
			| 0x400//PERMIT_VIEW_ALL_ORDERS
			| 0x800//PERMIT_VIEW_TRADE_PRICE
			| 0x1000//PERMIT_CONFIGURE_USER
			| 0x2000//PERMIT_CONFIGURE_ENTERPRISE
			| 0x4000//PERMIT_CONFIGURE_PRINT 

			| 0x1//PERMIT_LOGIN_PDA 
			| 0x2//PERMIT_ADMIN_APP_PC 
			| 0x4;//PERMIT_MANAGE_USER;

			return $permit;
	}

	public static function getAllPermits(){
		$tag = '1,2,4,8,16,32,64,128,256,512,1024,2048,4096,8192,16384';
		return PermitHelper::permitInfo($tag);
	}

	public static function permitInfo($permits){
		$permitInfoValue = array(
			0x01=>0x01,//1
			0x02=>0x02,//2
			0x04=>0x04,//4
			0x08=>0x08,//8
			0x10=>0x10,//16
			0x20=>0x20,//32
			0x40=>0x40,//64
			0x80=>0x80,//128
			0x100=>0x100,//256
			0x200=>0x200,//512
			0x400=>0x400,//1024
			0x800=>0x800,//2048
			0x1000=>0x1000,//4096
			0x2000=>0x2000,//8192
			0x4000=>0x4000//16384
			);
		//print_r($permitInfoValue);
		$permitValue = 0;
		$list = split(',', $permits);
		foreach ($list as $key => $value) {
			$permitValue = $permitValue | $permitInfoValue[$value];
		}
		return $permitValue;
	}


	public static function permits($role,$permits){
		$list = split(',', $permits);
		
		switch ($role) {
			case 1:
				$permitInfo =
				0x8//PERMIT_RECEIPT
				| 0x10//PERMIT_WAREHOUSE
				| 0x20//PERMIT_VIEW_COST_PRICE
				| 0x40//PERMIT_UPDATE_ALL_ORDERS
				| 0x80//PERMIT_UPDATE_DELETE_ORDERS
				| 0x100//PERMIT_ADD_GOODS
				| 0x200//PERMIT_VIEW_ALL_CUSTOMERS
				| 0x400//PERMIT_VIEW_ALL_ORDERS
				| 0x800//PERMIT_VIEW_TRADE_PRICE
				| 0x1000//PERMIT_CONFIGURE_USER
				| 0x2000//PERMIT_CONFIGURE_ENTERPRISE
				| 0x4000;//PERMIT_CONFIGURE_PRINT 

				for ($index=0; $index < sizeof($list); $index++) { 
					#echo "string ".$list[$index].'  '.$permitInfo;
					if($list[$index] == 1){
						$permitInfo = $permitInfo | 0x1;
					}else if($list[$index] == 2){
						$permitInfo = $permitInfo | 0x2;
					}else if($list[$index] == 4){
						$permitInfo = $permitInfo | 0x4;
					}
				}
				break;
			case 2:

				$permitInfo = 
				0x1//PERMIT_LOGIN_PDA
				| 0x40//PERMIT_UPDATE_ALL_ORDERS
				| 0x80//PERMIT_UPDATE_DELETE_ORDERS
				| 0x100//PERMIT_ADD_GOODS
				| 0x200//PERMIT_VIEW_ALL_CUSTOMERS
				| 0x400//PERMIT_VIEW_ALL_ORDERS
				| 0x800//PERMIT_VIEW_TRADE_PRICE
				;
				for ($index=0; $index < sizeof($list); $index++) { 
					if($list[$index] == 0x8){
						$permitInfo = $permitInfo | 0x8;
					}else if($list[$index] == 0x10){
						$permitInfo = $permitInfo | 0x10;
					}else if($list[$index] == 0x20){
						$permitInfo = $permitInfo | 0x20;
					}
				}
				break;
			case 3:

				$permitInfo = 
					0x1//PERMIT_LOGIN_PDA
					| 0x800//PERMIT_VIEW_TRADE_PRICE;
					;
				for ($index=0; $index < sizeof($list); $index++) { 
					if($list[$index] == 0x80){
						$permitInfo = $permitInfo | 0x80;
					}else if($list[$index] == 0x100){
						$permitInfo = $permitInfo | 0x100;
					}else if($list[$index] == 0x200){
						$permitInfo = $permitInfo | 0x200;
					}
				}
				break;
			case 4:
				$permitInfo = 
					0x1//PERMIT_LOGIN_PDA
					| 0x100//PERMIT_ADD_GOODS
					| 0x400//PERMIT_VIEW_ALL_ORDERS;
					;
					#echo "string =".$permitInfo.'  = ';
				for ($index=0; $index < sizeof($list); $index++) {
					if($list[$index] == 0x800){
						$permitInfo = $permitInfo | 0x800;
					}
				}
				break;

		}
		return $permitInfo;
	}

	public static function getPermitInfo(){
		$permitInfo = array(array('tag'=>'boss','id'=>'1','name'=>'老板','role'=>PermitHelper::getBoss()),
							array('tag'=>'shopOwner','id'=>'2','name'=>'店长','role'=>PermitHelper::getShopOwner()),
							array('tag'=>'salesman','id'=>'3','name'=>'店员','role'=>PermitHelper::getSalesman()),
							array('tag'=>'warKeeper','id'=>'4','name'=>'仓库管理员','role'=>PermitHelper::getWarKeeper())
							);
		return $permitInfo;
	}

	public static function getPermitAllInfo(){
		$permitAllInfo =array(
			array('name'=>'登陆手机','id'=>0x1),
			array('name'=>'手机报表','id'=>0x2),
			
			array('name'=>'创建商品','id'=>0x100),
			array('name'=>'入库','id'=>0x10),
			array('name'=>'查看成本价','id'=>0x20),
			array('name'=>'查看单价(批发)','id'=>0x800),

			array('name'=>'查看订单','id'=>0x400),
			array('name'=>'修改订单','id'=>0x40),
			array('name'=>'修改/删除订单','id'=>0x80),
			array('name'=>'收款','id'=>0x8),

			array('name'=>'查看客户','id'=>0x200),
			
			array('name'=>'属性配置','id'=>0x1000),
			array('name'=>'企业资料','id'=>0x2000),
			array('name'=>'打印配置','id'=>0x4000),
			array('name'=>'用户管理','id'=>0x4));

		return $permitAllInfo;
	}
    
    //权限列表
    public function getPermitAll(){
        $permitAll =array();
        
     //基础配置
        $setterRoleList = array();
        //1、企业资料
        $enterpriseRoleList['content'] = array(array('name'=>'查看','id'=>0x2000),array('name'=>'编辑','id'=>0x1));
        $enterpriseRoleList['name'] = "企业资料";
        //2、店铺管理
        $shopRoleList['content'] = array(array('name'=>'查看','id'=>0x8),array('name'=>'编辑','id'=>0x10));
        $shopRoleList['name'] = "店铺管理";
        //3、仓库管理
        $warehouseRoleList['content'] = array(array('name'=>'查看','id'=>0x20),array('name'=>'编辑','id'=>0x40));
        $warehouseRoleList['name'] = "仓库管理";
        //4、用户管理
        $userRoleList['content'] = array(array('name'=>'查看','id'=>0x4),array('name'=>'编辑','id'=>0x80));
        $userRoleList['name'] = "用户管理";
        //5、属性配置
        $configRoleList['content'] = array(array('name'=>'查看','id'=>0x1000),array('name'=>'编辑','id'=>0x100));
        $configRoleList['name'] = "属性配置";
        //6、打印设置
        $printRoleList['content'] = array(array('name'=>'查看','id'=>0x4000),array('name'=>'编辑','id'=>0x200));
        $printRoleList['name'] = "打印设置";
        //7、微信报表
        $weixinRoleList['content'] = array(array('name'=>'查看','id'=>0x2));
        $weixinRoleList['name'] = "微信报表";
        //8、经营概括
        $shopSalaRoleList['content'] = array(array('name'=>'查看','id'=>0x400));
        $shopSalaRoleList['name'] = "经营概括";
        //9、日报表
        $shopTodaySalaRoleList['content'] = array(array('name'=>'查看','id'=>0x800));
        $shopTodaySalaRoleList['name'] = "日报表";
        //10、月报表
        $shopMonthSalaRoleList['content'] = array(array('name'=>'查看','id'=>0x200000));
        $shopMonthSalaRoleList['name'] = "月报表";
        //11、供应商对账
        $supplierReceiptRoleList['content'] = array(array('name'=>'查看','id'=>0x8000),array('name'=>'付款','id'=>0x10000));
        $supplierReceiptRoleList['name'] = "供应商对账";
        //12、客户对账
        $customerReceiptRoleList['content'] = array(array('name'=>'查看','id'=>0x20000),array('name'=>'付款','id'=>0x40000));
        $customerReceiptRoleList['name'] = "客户对账";
        //13、客户销量报表
        $customerSalaRoleList['content'] = array(array('name'=>'查看','id'=>0x80000));
        $customerSalaRoleList['name'] = "客户销量报表";
        //14、商品销量报表
        $goodsSalaRoleList['content'] = array(array('name'=>'查看','id'=>0x100000));
        $goodsSalaRoleList['name'] = "商品销量报表";
        //15、角色管理
        $roleSetterRoleList['content'] = array(array('name'=>'查看','id'=>0x400000),array('name'=>'编辑','id'=>0x800000));
        $roleSetterRoleList['name'] = "角色管理";
		//16、供应商采购报表
		$supplierPurchaseRoleList['content'] = array(array('name'=>'查看','id'=>0x1000000));
        $supplierPurchaseRoleList['name'] = "供应商采购报表";
        
        $setterRoleList['name'] = "基础设置";
        
        $setterRoleList['content'][0] = $enterpriseRoleList;//1、企业资料
        $setterRoleList['content'][1] = $shopRoleList;//2、店铺管理
        $setterRoleList['content'][2] = $warehouseRoleList;//3、仓库管理
        $setterRoleList['content'][3] = $userRoleList;//4、用户管理
        $setterRoleList['content'][4] = $configRoleList;//5、属性配置
        $setterRoleList['content'][5] = $printRoleList;//6、打印设置
        $setterRoleList['content'][6] = $weixinRoleList;//7、微信报表
        $setterRoleList['content'][7] = $shopSalaRoleList;//8、经营概括
        $setterRoleList['content'][8] = $shopTodaySalaRoleList;//9、日报表
        $setterRoleList['content'][9] = $shopMonthSalaRoleList;//10、月报表
        $setterRoleList['content'][10] = $supplierReceiptRoleList;//11、供应商对账
        $setterRoleList['content'][11] = $customerReceiptRoleList;//12、客户对账
        $setterRoleList['content'][12] = $customerSalaRoleList;//13、客户销量报表
        $setterRoleList['content'][13] = $goodsSalaRoleList;//14、商品销量报表
        $setterRoleList['content'][14] = $roleSetterRoleList;//15、角色管理
		$setterRoleList['content'][15] = $supplierPurchaseRoleList;//15、角色管理
    //销售管理
        $salesRoleList = array();
        //1、销售单
        $saleOrderRoleList['content'] = array(
                                            array('name'=>'查看','id'=>0x1),
                                            array('name'=>'查看销售价','id'=>0x2),
                                            array('name'=>'新增','id'=>0x4),
                                            array('name'=>'新增草稿','id'=>0x8),
                                            array('name'=>'删除','id'=>0x80),
                                            array('name'=>'打印','id'=>0x10),
                                            array('name'=>'删除草稿','id'=>0x20),
                                        );
        $saleOrderRoleList['name'] = "销售单";
        //2、销售退货单
        $returnOrderRoleList['content'] = array(
                                            array('name'=>'查看','id'=>0x40),
                                            array('name'=>'查看退货价','id'=>0x100),
                                            array('name'=>'新增','id'=>0x200),
                                            array('name'=>'新增草稿','id'=>0x400),
                                            array('name'=>'删除','id'=>0x800),
                                            array('name'=>'打印','id'=>0x2000),
                                            array('name'=>'删除草稿','id'=>0x1000),
                                        );
        $returnOrderRoleList['name'] = "销售退货单";
        //3、客户管理
        $customerManageRoleList['content'] = array(
                                            array('name'=>'查看','id'=>0x4000),
                                            array('name'=>'编辑','id'=>0x8000),
                                            array('name'=>'导入','id'=>0x10000),
                                            array('name'=>'导出','id'=>0x20000),
                                            array('name'=>'资金流水','id'=>0x80000),
                                            array('name'=>'客户销售明细','id'=>0x100000),
                                        );
        $customerManageRoleList['name'] = "客户管理";
        //4、客户分类
        $customerGroupRoleList['content'] = array(
                                            array('name'=>'查看','id'=>0x40000),
                                            array('name'=>'编辑','id'=>0x200000),
                                        );
        $customerGroupRoleList['name'] = "客户分类";
        
        $salesRoleList['name'] = "销售管理";
        $salesRoleList['content'][0] = $saleOrderRoleList;//1、销售单
        $salesRoleList['content'][1] = $returnOrderRoleList;//2、销售退货单
        $salesRoleList['content'][2] = $customerManageRoleList;//3、客户管理
        $salesRoleList['content'][3] = $customerGroupRoleList;//4、客户分类
        
    //采购管理
        $purchaseRoleList = array();
        //1、采购单
        $purchaseOrderRoleList['content'] = array(
                                            array('name'=>'查看','id'=>0x1),
                                            array('name'=>'查看采购价','id'=>0x2),
                                            array('name'=>'新增采购','id'=>0x4),
                                            array('name'=>'新增草稿','id'=>0x8),
                                            array('name'=>'删除','id'=>0x80),
                                            array('name'=>'打印','id'=>0x10),
                                            array('name'=>'删除草稿','id'=>0x20),
                                        );
        $purchaseOrderRoleList['name'] = "采购单";
        //2、采购退货单
        $returnPurchaseOrderRoleList['content'] = array(
                                            array('name'=>'查看','id'=>0x40),
                                            array('name'=>'查看采购退货价','id'=>0x100),
                                            array('name'=>'新增退采购','id'=>0x100000),
                                            array('name'=>'新增草稿','id'=>0x400),
                                            array('name'=>'删除','id'=>0x800),
                                            array('name'=>'打印','id'=>0x2000),
                                            array('name'=>'删除草稿','id'=>0x1000),
                                        );
        $returnPurchaseOrderRoleList['name'] = "采购退货单";
        //3、供应商管理
        $supplierManageRoleList['content'] = array(
                                            array('name'=>'查看','id'=>0x4000),
                                            array('name'=>'编辑','id'=>0x8000),
                                            array('name'=>'导入','id'=>0x10000),
                                            array('name'=>'导出','id'=>0x20000),
                                            array('name'=>'供应商采购明细','id'=>0x80000),
                                        );
        $supplierManageRoleList['name'] = "供应商管理";
        //4、供应商分类
        $supplierGroupRoleList['content'] = array(
                                            array('name'=>'查看','id'=>0x40000),
                                            array('name'=>'编辑','id'=>0x200000),
                                        );
        $supplierGroupRoleList['name'] = "供应商分类";
        
        $purchaseRoleList['name'] = "采购管理";
        $purchaseRoleList['content'][0] = $purchaseOrderRoleList;//1、采购单
        $purchaseRoleList['content'][1] = $returnPurchaseOrderRoleList;//2、采购退货单
        $purchaseRoleList['content'][2] = $supplierManageRoleList;//3、供应商管理
        $purchaseRoleList['content'][3] = $supplierGroupRoleList;//4、供应商分类
        
    //仓库管理
        $warehouseRoleLis = array();
        //1、仓库查询
        $warehouseGoodsRoleList['content'] = array(
                                            array('name'=>'查看','id'=>0x2),
                                            array('name'=>'库存总成本汇总','id'=>0x4),
                                        );
        $warehouseGoodsRoleList['name'] = "仓库查询";
        //2、库存调拨单
        $returnPurchaseOrderRoleList['content'] = array(
                                            array('name'=>'查看','id'=>0x20),
                                            array('name'=>'新增调拨','id'=>0x80),
                                            array('name'=>'新增草稿','id'=>0x40),
                                            array('name'=>'删除','id'=>0x1000),
                                            array('name'=>'打印','id'=>0x8),
                                            array('name'=>'删除草稿','id'=>0x8000),
                                        );
        $returnPurchaseOrderRoleList['name'] = "库存调拨单";
        //3、库存流水
        $inoutRoleList['content'] = array(
                                    array('name'=>'查看','id'=>0x2000),
                                );
        $inoutRoleList['name'] = "库存流水";
        //4、商品
        $goodsRoleList['content'] = array(
                                    array('name'=>'查看','id'=>0x1),
                                    array('name'=>'新增','id'=>0x100),
                                    array('name'=>'修改','id'=>0x200),
                                    //array('name'=>'入库','id'=>0x10),
                                    array('name'=>'删除','id'=>0x400),
                                    array('name'=>'查看成本价','id'=>0x4000),
                                    array('name'=>'查看批发价','id'=>0x800),
                                );
        $goodsRoleList['name'] = "商品";
        
        //5、商品出库
        $goodsOutWarehouse['content'] = array(
                                    array('name'=>'查看','id'=>0x10000),
                                    array('name'=>'出库','id'=>0x20000)
                                );
        $goodsOutWarehouse['name'] = "商品出库";
        
        //6、商品入库
        $goodsInWarehouse['content'] = array(
                                    array('name'=>'查看','id'=>0x40000),
                                    array('name'=>'入库','id'=>0x10)
                                );
        $goodsInWarehouse['name'] = "商品入库";
        
        $warehouseRoleLis['name'] = "仓库管理";
        $warehouseRoleLis['content'][0] = $warehouseGoodsRoleList;//1、仓库查询
        $warehouseRoleLis['content'][1] = $returnPurchaseOrderRoleList;//2、库存调拨单
        $warehouseRoleLis['content'][2] = $inoutRoleList;//3、库存流水
        $warehouseRoleLis['content'][3] = $goodsRoleList;//4、商品
        $warehouseRoleLis['content'][4] = $goodsOutWarehouse;//5、商品出库
        $warehouseRoleLis['content'][5] = $goodsInWarehouse;//6、商品入库
        
    //总权限数组
        $permitAll["setterRoleList"] = $setterRoleList;//基础配置
        $permitAll["salesRoleList"] = $salesRoleList;//销售管理
        $permitAll["purchaseRoleList"] = $purchaseRoleList;//采购管理
        $permitAll["warehouseRoleLis"] = $warehouseRoleLis;//仓库管理
        
        return $permitAll;
    }
}