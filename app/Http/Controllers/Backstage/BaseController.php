<?php 
namespace App\Http\Controllers\Backstage;
use Session;
use Config;
use Auth;
use Redirect;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\PermitHelper;
class BaseController extends Controller {
    protected $redirect_url = "/backstage/index";
    protected $arr_title = array();
    public function __construct()
    {}
    public function backstage_auth()
    {
        if(!Session::get('uid'))
        {
            // header("Location:/special/login");
            // exit();
            Redirect::to('/backstage/login')->send();
            exit;
        }
        else
        {
            return redirect("backstage/index");
        }
    }

    //生成token令牌
    function createToken($enterprise,$user) {
        $arr['exp_date'] = get_ms();
        $arr['token'] = md5(sha1("y8TWzAiCZ8yTZF6rJfYgNRiCfF8cHkgg".$arr['exp_date'].rand()));
        $arr['enterprise'] = $enterprise;
        $arr['user'] = $user;
        DB::table("pub_token")->insert($arr);
        $data['exp_date'] = $arr['exp_date'];
        $data['token'] = $arr['token'];
        return $data;
    }
    
    //token令牌验证
    function checkToken($token){
        if(Cache::get($token)){
            $data = array(true,"验证通过");            
        }else{
            $data = array(false,"令牌密匙已失效");
        }
        return $data;
    }
    
    /**
     * 判断设置模块是否有权限
     *
     * @param permit
     * @return Boolean
     */
    private function isSetterPermit($permit){
        $setterPermit = Session::get("setterPermits");
        if($setterPermit == -1){
            return true;
        }else{
            if(($setterPermit & $permit) > 0){
                return true;
            }else{
                return false;
            }
        }
    }
    
    /**
     * 判断销售模块是否有权限
     *
     * @param permit
     * @return Boolean
     */
    private function isSalesPermit($permit){
        $salesPermit = Session::get("salesPermits");
        if($salesPermit == -1){
            return true;
        }else{
            if(($salesPermit & $permit) > 0){
                return true;
            }else{
                return false;
            }
        }
    }
    
    /**
     * 判断采购模块是否有权限
     *
     * @param permit
     * @return Boolean
     */
    private function isPurchasePermit($permit){
        $purchasePermit = Session::get("purchasePermits");
        if($purchasePermit == -1){
            return true;
        }else{
            if(($purchasePermit & $permit) > 0){
                return true;
            }else{
                return false;
            }
        }
    }
    
    /**
     * 判断仓库模块是否有权限
     *
     * @param permit
     * @return Boolean
     */
    private function isWarehousePermit($permit){
        $warehousePermit = Session::get("warehousePermits");
        if($warehousePermit == -1){
            return true;
        }else{
            if(($warehousePermit & $permit) > 0){
                return true;
            }else{
                return false;
            }
        }
    }
    
    //****************************** 设置模块 ******************************
    
    //企业资料查看
    public function showEnterpriseMessagePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showEnterpriseMessagePermitId = $allPermit["setterRoleList"]["content"][0]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showEnterpriseMessagePermitId);
        return $isPermit;
    }
    
    //企业资料编辑
    public function editEnterpriseMessagePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $editEnterpriseMessagePermitId = $allPermit["setterRoleList"]["content"][0]["content"][1]["id"];
        $isPermit = $this->isSetterPermit($editEnterpriseMessagePermitId);
        return $isPermit;
    }
    
    //店铺管理查看
    public function showShopPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showShopPermitId = $allPermit["setterRoleList"]["content"][1]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showShopPermitId);
        return $isPermit;
    }
    
    //店铺管理编辑
    public function editShopPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $editShopPermitId = $allPermit["setterRoleList"]["content"][1]["content"][1]["id"];
        $isPermit = $this->isSetterPermit($editShopPermitId);
        return $isPermit;
    }
    
    //仓库管理查看
    public function showWarehousePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showWarehousePermitId = $allPermit["setterRoleList"]["content"][2]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showWarehousePermitId);
        return $isPermit;
    }
    
    //仓库管理编辑
    public function editWarehousePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $editWarehousePermitId = $allPermit["setterRoleList"]["content"][2]["content"][1]["id"];
        $isPermit = $this->isSetterPermit($editWarehousePermitId);
        return $isPermit;
    }
    
    //用户管理查看
    public function showUserPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showUserPermitId = $allPermit["setterRoleList"]["content"][3]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showUserPermitId);
        return $isPermit;
    }
    
    //用户管理编辑
    public function editUserPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $editUserPermitId = $allPermit["setterRoleList"]["content"][3]["content"][1]["id"];
        $isPermit = $this->isSetterPermit($editUserPermitId);
        return $isPermit;
    }
    
    //属性配置查看
    public function showPropertyPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showPropertyPermitId = $allPermit["setterRoleList"]["content"][4]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showPropertyPermitId);
        return $isPermit;
    }
    
    //属性配置编辑
    public function editPropertyPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $editPropertyPermitId = $allPermit["setterRoleList"]["content"][4]["content"][1]["id"];
        $isPermit = $this->isSetterPermit($editPropertyPermitId);
        return $isPermit;
    }
    
    //打印设置查看
    public function showPrintPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showPrintPermitId = $allPermit["setterRoleList"]["content"][5]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showPrintPermitId);
        return $isPermit;
    }
    
    //打印设置编辑
    public function editPrintPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $editPrintPermitId = $allPermit["setterRoleList"]["content"][5]["content"][1]["id"];
        $isPermit = $this->isSetterPermit($editPrintPermitId);
        return $isPermit;
    }
    
    //微信报表查看
    public function showWechatReportPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showWechatReportPermitId = $allPermit["setterRoleList"]["content"][6]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showWechatReportPermitId);
        return $isPermit;
    }
    
    //经营概括查看
    public function showSurveyPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showSurveyPermitId = $allPermit["setterRoleList"]["content"][7]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showSurveyPermitId);
        return $isPermit;
    }
    
    //日报表查看
    public function showDayReportPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showDayReportPermitId = $allPermit["setterRoleList"]["content"][8]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showDayReportPermitId);
        return $isPermit;
    }
    
    //月报表查看
    public function showMonthReportPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showMonthReportPermitId = $allPermit["setterRoleList"]["content"][9]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showMonthReportPermitId);
        return $isPermit;
    }
    
    //供应商对账查看
    public function showSupplierBalancePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showSupplierBalancePermitId = $allPermit["setterRoleList"]["content"][10]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showSupplierBalancePermitId);
        return $isPermit;
    }
    
    //供应商对账付款
    public function showSupplierPayPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showSupplierPayPermitId = $allPermit["setterRoleList"]["content"][10]["content"][1]["id"];
        $isPermit = $this->isSetterPermit($showSupplierPayPermitId);
        return $isPermit;
    }
    
    
    //客户对账查看
    public function showCustomerBalancePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showCustomerBalancePermitId = $allPermit["setterRoleList"]["content"][11]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showCustomerBalancePermitId);
        return $isPermit;
    }
    
    //客户对账付款
    public function showCustomerPayPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showCustomerPayPermitId = $allPermit["setterRoleList"]["content"][11]["content"][1]["id"];
        $isPermit = $this->isSetterPermit($showCustomerPayPermitId);
        return $isPermit;
    }
    
    //客户销量报表查看
    public function showCustomerSalesReportPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showCustomeSalesReportPermitId = $allPermit["setterRoleList"]["content"][12]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showCustomeSalesReportPermitId);
        return $isPermit;
    }
    
    //商品销量报表查看
    public function showGoodsSalesReportPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showGoodsSalesReportPermitId = $allPermit["setterRoleList"]["content"][13]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showGoodsSalesReportPermitId);
        return $isPermit;
    }
    
    //角色管理查看
    public function showRolePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showRolePermitId = $allPermit["setterRoleList"]["content"][14]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showRolePermitId);
        return $isPermit;
    }
    
    //角色管理编辑
    public function editRolePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $editRolePermitId = $allPermit["setterRoleList"]["content"][14]["content"][1]["id"];
        $isPermit = $this->isSetterPermit($editRolePermitId);
        return $isPermit;
    }
    
    //供应商采购报表查看
    public function showSupplierPurchaseReportPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showSupplierPurchaseReportPermitId = $allPermit["setterRoleList"]["content"][15]["content"][0]["id"];
        $isPermit = $this->isSetterPermit($showSupplierPurchaseReportPermitId);
        return $isPermit;
    }
    
    
    //****************************** 销售模块 ******************************
    
    //销售单查看
    public function showSaleOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showSaleOrderPermitId = $allPermit["salesRoleList"]["content"][0]["content"][0]["id"];
        $isPermit = $this->isSalesPermit($showSaleOrderPermitId);
        return $isPermit;
    }
    
    //销售单销售价查看
    public function showSaleOrderPricePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showSaleOrderPricePermitId = $allPermit["salesRoleList"]["content"][0]["content"][1]["id"];
        $isPermit = $this->isSalesPermit($showSaleOrderPricePermitId);
        return $isPermit;
    }
    
    //销售单新增
    public function addSaleOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $addSaleOrderPermitId = $allPermit["salesRoleList"]["content"][0]["content"][2]["id"];
        $isPermit = $this->isSalesPermit($addSaleOrderPermitId);
        return $isPermit;
    }
    
    //销售草稿单新增
    public function addDraftSaleOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $addDraftSaleOrderPermitId = $allPermit["salesRoleList"]["content"][0]["content"][3]["id"];
        $isPermit = $this->isSalesPermit($addDraftSaleOrderPermitId);
        return $isPermit;
    }
    
    //销售单删除
    public function delSaleOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $delSaleOrderPermitId = $allPermit["salesRoleList"]["content"][0]["content"][4]["id"];
        $isPermit = $this->isSalesPermit($delSaleOrderPermitId);
        return $isPermit;
    }
    
    //销售单打印
    public function printSaleOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $printSaleOrderPermitId = $allPermit["salesRoleList"]["content"][0]["content"][5]["id"];
        $isPermit = $this->isSalesPermit($printSaleOrderPermitId);
        return $isPermit;
    }
    
    //销售草稿单删除
    public function delDraftSaleOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $delDraftSaleOrderPermitId = $allPermit["salesRoleList"]["content"][0]["content"][6]["id"];
        $isPermit = $this->isSalesPermit($delDraftSaleOrderPermitId);
        return $isPermit;
    }
    
    //销售退货单查看
    public function showRefundSaleOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showRefundSaleOrderPermitId = $allPermit["salesRoleList"]["content"][1]["content"][0]["id"];
        $isPermit = $this->isSalesPermit($showRefundSaleOrderPermitId);
        return $isPermit;
    }
    
    //销售退货单退货价查看
    public function showRefundSaleOrderPricePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showRefundSaleOrderPricePermitId = $allPermit["salesRoleList"]["content"][1]["content"][1]["id"];
        $isPermit = $this->isSalesPermit($showRefundSaleOrderPricePermitId);
        return $isPermit;
    }
    
    //销售退货单新增
    public function addRefundSaleOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $addRefundSaleOrderPermitId = $allPermit["salesRoleList"]["content"][1]["content"][2]["id"];
        $isPermit = $this->isSalesPermit($addRefundSaleOrderPermitId);
        return $isPermit;
    }
    
    //销售退货草稿单新增
    public function addDraftRefundSaleOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $addDraftRefundSaleOrderPermitId = $allPermit["salesRoleList"]["content"][1]["content"][3]["id"];
        $isPermit = $this->isSalesPermit($addDraftRefundSaleOrderPermitId);
        return $isPermit;
    }
    
    //销售退货单删除
    public function delRefundSaleOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $delRefundSaleOrderPermitId = $allPermit["salesRoleList"]["content"][1]["content"][4]["id"];
        $isPermit = $this->isSalesPermit($delRefundSaleOrderPermitId);
        return $isPermit;
    }
    
    //销售退货单打印
    public function printRefundSaleOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $printRefundSaleOrderPermitId = $allPermit["salesRoleList"]["content"][1]["content"][5]["id"];
        $isPermit = $this->isSalesPermit($printRefundSaleOrderPermitId);
        return $isPermit;
    }
    
    //销售退货草稿单删除
    public function delDraftRefundSaleOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $delDraftRefundSaleOrderPermitId = $allPermit["salesRoleList"]["content"][1]["content"][6]["id"];
        $isPermit = $this->isSalesPermit($delDraftRefundSaleOrderPermitId);
        return $isPermit;
    }
    
    //客户管理查看
    public function showCustomerPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showCustomerPermitId = $allPermit["salesRoleList"]["content"][2]["content"][0]["id"];
        $isPermit = $this->isSalesPermit($showCustomerPermitId);
        return $isPermit;
    }
    
    //客户编辑
    public function editCustomerPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $editCustomerPermitId = $allPermit["salesRoleList"]["content"][2]["content"][1]["id"];
        $isPermit = $this->isSalesPermit($editCustomerPermitId);
        return $isPermit;
    }
    
    //客户导入
    public function importCustomerPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $importCustomerPermitId = $allPermit["salesRoleList"]["content"][2]["content"][2]["id"];
        $isPermit = $this->isSalesPermit($importCustomerPermitId);
        return $isPermit;
    }
    
    //客户导出
    public function exportCustomerPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $exportCustomerPermitId = $allPermit["salesRoleList"]["content"][2]["content"][3]["id"];
        $isPermit = $this->isSalesPermit($exportCustomerPermitId);
        return $isPermit;
    }
    
    //客户资金流水
    public function showCustomerCapitalFlowPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showCustomerCapitalFlowPermitId = $allPermit["salesRoleList"]["content"][2]["content"][4]["id"];
        $isPermit = $this->isSalesPermit($showCustomerCapitalFlowPermitId);
        return $isPermit;
    }
    
    //客户销售明细
    public function showCustomerSaleDtlPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showCustomerSaleDtlPermitId = $allPermit["salesRoleList"]["content"][2]["content"][5]["id"];
        $isPermit = $this->isSalesPermit($showCustomerSaleDtlPermitId);
        return $isPermit;
    }
    
    //客户分类查看
    public function showCustomerClassificationPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showCustomerClassificationPermitId = $allPermit["salesRoleList"]["content"][3]["content"][0]["id"];
        $isPermit = $this->isSalesPermit($showCustomerClassificationPermitId);
        return $isPermit;
    }
    
    //客户分类编辑
    public function editCustomerClassificationPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $editCustomerClassificationPermitId = $allPermit["salesRoleList"]["content"][3]["content"][1]["id"];
        $isPermit = $this->isSalesPermit($editCustomerClassificationPermitId);
        return $isPermit;
    }
    
    
    //****************************** 采购模块 ******************************
    
    //采购单查看
    public function showPurchaseOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showPurchaseOrderPermitId = $allPermit["purchaseRoleList"]["content"][0]["content"][0]["id"];
        $isPermit = $this->isPurchasePermit($showPurchaseOrderPermitId);
        return $isPermit;
    }
    
    //采购单采购价查看
    public function showPurchaseOrderPricePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showPurchaseOrderPricePermitId = $allPermit["purchaseRoleList"]["content"][0]["content"][1]["id"];
        $isPermit = $this->isPurchasePermit($showPurchaseOrderPricePermitId);
        return $isPermit;
    }
    
    //采购单新增
    public function addPurchaseOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $addPurchaseOrderPermitId = $allPermit["purchaseRoleList"]["content"][0]["content"][2]["id"];
        $isPermit = $this->isPurchasePermit($addPurchaseOrderPermitId);
        return $isPermit;
    }
    
    //采购草稿单新增
    public function addDraftPurchaseOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $addDraftPurchaseOrderPermitId = $allPermit["purchaseRoleList"]["content"][0]["content"][3]["id"];
        $isPermit = $this->isPurchasePermit($addDraftPurchaseOrderPermitId);
        return $isPermit;
    }
    
    //采购单删除
    public function delPurchaseOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $delPurchaseOrderPermitId = $allPermit["purchaseRoleList"]["content"][0]["content"][4]["id"];
        $isPermit = $this->isPurchasePermit($delPurchaseOrderPermitId);
        return $isPermit;
    }
    
    //采购单打印
    public function printPurchaseOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $printPurchaseOrderPermitId = $allPermit["purchaseRoleList"]["content"][0]["content"][5]["id"];
        $isPermit = $this->isPurchasePermit($printPurchaseOrderPermitId);
        return $isPermit;
    }
    
    //采购草稿单删除
    public function delDraftPurchaseOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $delDraftPurchaseOrderPermitId = $allPermit["purchaseRoleList"]["content"][0]["content"][6]["id"];
        $isPermit = $this->isPurchasePermit($delDraftPurchaseOrderPermitId);
        return $isPermit;
    }
    
    //采购退货单查看
    public function showRefundPurchaseOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showRefundPurchaseOrderPermitId = $allPermit["purchaseRoleList"]["content"][1]["content"][0]["id"];
        $isPermit = $this->isPurchasePermit($showRefundPurchaseOrderPermitId);
        return $isPermit;
    }
    
    //采购退货价查看
    public function showRefundPurchaseOrderPricePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showRefundPurchaseOrderPricePermitId = $allPermit["purchaseRoleList"]["content"][1]["content"][1]["id"];
        $isPermit = $this->isPurchasePermit($showRefundPurchaseOrderPricePermitId);
        return $isPermit;
    }
    
    //采购退货单新增
    public function addRefundPurchaseOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $addRefundPurchaseOrderPermitId = $allPermit["purchaseRoleList"]["content"][1]["content"][2]["id"];
        $isPermit = $this->isPurchasePermit($addRefundPurchaseOrderPermitId);
        return $isPermit;
    }
    
    //采购退货草稿单新增
    public function addDraftRefundPurchaseOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $addDraftRefundPurchaseOrderPermitId = $allPermit["purchaseRoleList"]["content"][1]["content"][3]["id"];
        $isPermit = $this->isPurchasePermit($addDraftRefundPurchaseOrderPermitId);
        return $isPermit;
    }
    
    //采购退货单删除
    public function delRefundPurchaseOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $delRefundPurchaseOrderPermitId = $allPermit["purchaseRoleList"]["content"][1]["content"][4]["id"];
        $isPermit = $this->isPurchasePermit($delRefundPurchaseOrderPermitId);
        return $isPermit;
    }
    
    //采购退货单打印
    public function printRefundPurchaseOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $printRefundPurchaseOrderPermitId = $allPermit["purchaseRoleList"]["content"][1]["content"][5]["id"];
        $isPermit = $this->isPurchasePermit($printRefundPurchaseOrderPermitId);
        return $isPermit;
    }
    
    //采购退货草稿单删除
    public function delDraftRefundPurchaseOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $delDraftRefundPurchaseOrderPermitId = $allPermit["purchaseRoleList"]["content"][1]["content"][6]["id"];
        $isPermit = $this->isPurchasePermit($delDraftRefundPurchaseOrderPermitId);
        return $isPermit;
    }
    
    //供应商管理查看
    public function showSupplierPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showSupplierPermitId = $allPermit["purchaseRoleList"]["content"][2]["content"][0]["id"];
        $isPermit = $this->isPurchasePermit($showSupplierPermitId);
        return $isPermit;
    }
    
    //供应商编辑
    public function editSupplierPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $editSupplierPermitId = $allPermit["purchaseRoleList"]["content"][2]["content"][1]["id"];
        $isPermit = $this->isPurchasePermit($editSupplierPermitId);
        return $isPermit;
    }
    
    //供应商导入
    public function importSupplierPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $importSupplierPermitId = $allPermit["purchaseRoleList"]["content"][2]["content"][2]["id"];
        $isPermit = $this->isPurchasePermit($importSupplierPermitId);
        return $isPermit;
    }
    
    //供应商导出
    public function exportSupplierPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $exportSupplierPermitId = $allPermit["purchaseRoleList"]["content"][2]["content"][3]["id"];
        $isPermit = $this->isPurchasePermit($exportSupplierPermitId);
        return $isPermit;
    }
    
    //供应商销售明细
    public function showSupplierPurchaseDtlPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showSupplierPurchaseDtlPermitId = $allPermit["purchaseRoleList"]["content"][2]["content"][4]["id"];
        $isPermit = $this->isPurchasePermit($showSupplierPurchaseDtlPermitId);
        return $isPermit;
    }
    
    //供应商分类查看
    public function showSupplierClassificationPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showSupplierClassificationPermitId = $allPermit["purchaseRoleList"]["content"][3]["content"][0]["id"];
        $isPermit = $this->isPurchasePermit($showSupplierClassificationPermitId);
        return $isPermit;
    }
    
    //供应商分类编辑
    public function editSupplierClassificationPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $editSupplierClassificationPermitId = $allPermit["purchaseRoleList"]["content"][3]["content"][1]["id"];
        $isPermit = $this->isPurchasePermit($editSupplierClassificationPermitId);
        return $isPermit;
    }
    
    
    //****************************** 仓库模块 ******************************
    
    //仓库查看
    public function showWarehouseInfoPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showWarehousePermitId = $allPermit["warehouseRoleLis"]["content"][0]["content"][0]["id"];
        $isPermit = $this->isWarehousePermit($showWarehousePermitId);
        return $isPermit;
    }
    
    //库存总成本汇总查看
    public function showWarehouseTotalCostPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showWarehouseTotalCostPermitId = $allPermit["warehouseRoleLis"]["content"][0]["content"][1]["id"];
        $isPermit = $this->isWarehousePermit($showWarehouseTotalCostPermitId);
        return $isPermit;
    }
    
    //库存调拨单查看
    public function showAllocationOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showAllocationOrderPermitId = $allPermit["warehouseRoleLis"]["content"][1]["content"][0]["id"];
        $isPermit = $this->isWarehousePermit($showAllocationOrderPermitId);
        return $isPermit;
    }
    
    //库存调拨单新增
    public function addAllocationOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $addAllocationOrderPermitId = $allPermit["warehouseRoleLis"]["content"][1]["content"][1]["id"];
        $isPermit = $this->isWarehousePermit($addAllocationOrderPermitId);
        return $isPermit;
    }
    
    //库存调拨草稿单新增
    public function addDraftAllocationOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $addDraftAllocationOrderPermitId = $allPermit["warehouseRoleLis"]["content"][1]["content"][2]["id"];
        $isPermit = $this->isWarehousePermit($addDraftAllocationOrderPermitId);
        return $isPermit;
    }
    
    //库存调拨单删除
    public function delAllocationOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $delAllocationOrderPermitId = $allPermit["warehouseRoleLis"]["content"][1]["content"][3]["id"];
        $isPermit = $this->isWarehousePermit($delAllocationOrderPermitId);
        return $isPermit;
    }
    
    //库存调拨单打印
    public function printAllocationOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $printAllocationOrderPermitId = $allPermit["warehouseRoleLis"]["content"][1]["content"][4]["id"];
        $isPermit = $this->isWarehousePermit($printAllocationOrderPermitId);
        return $isPermit;
    }
    
    //库存调拨草稿单删除
    public function delDraftAllocationOrderPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $delDraftAllocationOrderPermitId = $allPermit["warehouseRoleLis"]["content"][1]["content"][5]["id"];
        $isPermit = $this->isWarehousePermit($delDraftAllocationOrderPermitId);
        return $isPermit;
    }
    
    //库存流水
    public function showInventoryFlowPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showInventoryFlowPermitId = $allPermit["warehouseRoleLis"]["content"][2]["content"][0]["id"];
        $isPermit = $this->isWarehousePermit($showInventoryFlowPermitId);
        return $isPermit;
    }
    
    //商品查看
    public function showGoodsPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showGoodsPermitId = $allPermit["warehouseRoleLis"]["content"][3]["content"][0]["id"];
        $isPermit = $this->isWarehousePermit($showGoodsPermitId);
        return $isPermit;
    }
    
    //商品新增
    public function addGoodsPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $addGoodsPermitId = $allPermit["warehouseRoleLis"]["content"][3]["content"][1]["id"];
        $isPermit = $this->isWarehousePermit($addGoodsPermitId);
        return $isPermit;
    }
    
    //商品修改
    public function modifyGoodsPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $modifyGoodsPermitId = $allPermit["warehouseRoleLis"]["content"][3]["content"][2]["id"];
        $isPermit = $this->isWarehousePermit($modifyGoodsPermitId);
        return $isPermit;
    }
    
    //商品删除
    public function delGoodsPermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $delGoodsPermitId = $allPermit["warehouseRoleLis"]["content"][3]["content"][3]["id"];
        $isPermit = $this->isWarehousePermit($delGoodsPermitId);
        return $isPermit;
    }
    
    //商品成本价查看
    public function showGoodsCostPricePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showGoodsCostPricePermitId = $allPermit["warehouseRoleLis"]["content"][3]["content"][4]["id"];
        $isPermit = $this->isWarehousePermit($showGoodsCostPricePermitId);
        return $isPermit;
    }
    
    //商品批发价查看
    public function showGoodsTradePricePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showGoodsTradePricePermitId = $allPermit["warehouseRoleLis"]["content"][3]["content"][5]["id"];
        $isPermit = $this->isWarehousePermit($showGoodsTradePricePermitId);
        return $isPermit;
    }
    
    //商品出库查看
    public function showGoodsOutWarehousePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showGoodsOutWarehousePermitId = $allPermit["warehouseRoleLis"]["content"][4]["content"][0]["id"];
        $isPermit = $this->isWarehousePermit($showGoodsOutWarehousePermitId);
        return $isPermit;
    }
    
    //商品出库
    public function addGoodsOutWarehousePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $addGoodsOutWarehousePermitId = $allPermit["warehouseRoleLis"]["content"][4]["content"][1]["id"];
        $isPermit = $this->isWarehousePermit($addGoodsOutWarehousePermitId);
        return $isPermit;
    }
    
    //商品入库查看
    public function showGoodsInWarehousePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $showGoodsOutWarehousePermitId = $allPermit["warehouseRoleLis"]["content"][5]["content"][0]["id"];
        $isPermit = $this->isWarehousePermit($showGoodsOutWarehousePermitId);
        return $isPermit;
    }
    
    //商品入库
    public function addGoodsInWarehousePermit(){
        $permitHelper = new PermitHelper();
        $allPermit = $permitHelper->getPermitAll();
        
        $addGoodsInWarehousePermitId = $allPermit["warehouseRoleLis"]["content"][5]["content"][1]["id"];
        $isPermit = $this->isWarehousePermit($addGoodsInWarehousePermitId);
        return $isPermit;
    }
}