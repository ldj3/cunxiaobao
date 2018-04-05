<?php namespace App\Models;
use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
class EntryCustomer extends Model {
    protected $table = 'base_customer';
    protected $kw = "";
    public $timestamps = false;
    
    //客户列表
    function customerList($enterpriseId,$customerType,$keyword,$page,$rows){
        $offset = ($page-1)*$rows;
        $query = DB::table("base_customer")
                   ->select("id","name","debt_money as debtMoney","trade_money_sum as tradeMoneySum","mobile","group","type","remark","reserve1 as discount","reserve2 as groupId")
                   ->where("enterprise",$enterpriseId)
                   ->where("is_deleted",0);
        if($customerType == "customer"){
            $query->where("type","<>","supplier");
        }else{
            $query->where("type",$customerType);
        }
        if($keyword){
            $this->kw = changeStr($keyword);
            $query->where(function($sql)
                    {
                        $sql->where("name","like",$this->kw)
                            ->orWhere("mobile","like",$this->kw);
                    });
        }
        $customers = $query->orderBy("create_date","desc")
                           ->skip($offset)
                           ->take($rows)
                           ->get();
        $total = $query->count();
        $customers = obj2arr($customers);
        $this->customer_type = Config::get("app.customer_type");
        for($i = 0;$i < count($customers);$i++){
            $customers[$i]["debtMoney"] = doubleval($customers[$i]["debtMoney"]);
            $customers[$i]["tradeMoneySum"] = doubleval($customers[$i]["tradeMoneySum"]);
            $customers[$i]["type"] = $this->customer_type[$customers[$i]["type"]];
        }
        $rt = array();
        $rt["data"] = $customers;
        $rt["total"] = $total;
        return $rt;
    }

    //客户折扣分组列表
    static function customerGroupList($enterpriseId,$customerType,$page,$rows,$keyword){
        $offset = ($page-1)*$rows;
        $query = DB::table("base_customer_group")
                   ->select("id","group_name as groupName","discount")
                   ->where("enterprise",$enterpriseId)
                   ->where("is_deleted",0)
                   ->where("customer_type",$customerType);
        if($keyword){
           $kw = changeStr($keyword);
           $query->where("group_name","like",$kw);
        }
        $customerGroups["total"] = $query->count();
        $customerGroups["data"] = $query->skip($offset)->take($rows)->get();
        $customerGroups["data"] = obj2arr($customerGroups["data"]);
        return $customerGroups;
    }
    
    //客户折扣分组详情
    static function findCstomerGroup($customerGroupId){
        $cstomerGroup = DB::table("base_customer_group")
                          ->where("id",$customerGroupId)
                          ->first();
        $cstomerGroup = obj2arr($cstomerGroup);
        return $cstomerGroup;
    }
    
    //客户折扣分组修改
    static function updateCustomerGroup($customerGroup,$customerGroupId){
        $rt = DB::table("base_customer_group")
                ->where("id",$customerGroupId)
                ->update($customerGroup);
        return $rt;
    }
    
    //对账流水列表
    function customerByBalanceList($enterpriseId,$customerType,$page,$rows,$keyword){
        $offset = ($page-1)*$rows;
        $query = DB::table("base_customer")
                   ->select("id as customerId","name as customerName","debt_money as debtMoney","trade_money_sum as tradeMoneySum","integral_sum as integralSum","current_integral as currentIntegral","mobile","operator","group")
                   ->where("enterprise",$enterpriseId)
                   ->where("is_deleted",0);
        if($customerType == "supplier"){
            $query->where("type",$customerType);
        }else{
            $query->where("type","<>","supplier");
        }
        if($keyword){
           $this->kw = changeStr($keyword);
           $query->where(function($sql){
                            $sql->where("name","like",$this->kw)
                                ->orWhere("mobile","like",$this->kw);
                        });
        }
        $query->orderBy('debt_money',"desc")->skip($offset)->take($rows);
        $customers = $query->get();
        return $customers;
    }
    
    //对账流水统计
    function customerStatistic($enterpriseId,$customerType,$keyword){
        $query = DB::table("base_customer")
                   ->where("enterprise",$enterpriseId)
                   ->where("is_deleted",0);
        if($customerType == "supplier"){
            $query->where("type",$customerType);
        }else{
            $query->where("type","<>","supplier");
        }
        if($keyword){
           $this->kw = changeStr($keyword);
           $query->where(function($sql){
                            $sql->where("name","like",$this->kw)
                                ->orWhere("mobile","like",$this->kw);
                        });
        }
        $rt["customerNum"] = $query->count();
        $rt["sumMoney"] = $query->sum("debt_money");
        $rt["sumMoney"] = doubleval($rt["sumMoney"]);
        $rt["customerCount"] = $query->where("debt_money",">",0)->count();
        return $rt;
    }
	
	//应收客户款
	function customerDebt($enterpriseId){
		$customerDebt = DB::table("base_customer")
						  ->where("enterprise",$enterpriseId)
                          ->where("is_deleted",0)
                          ->where("type","<>","supplier")
                          ->where("debt_money",">",0)
                          ->sum("debt_money");
		$customerDebt = doubleval($customerDebt);
		return $customerDebt;
	}
	
	//应付供应商总款
	function paidForSupplier($enterpriseId){
        $paidForSupplier = DB::table("base_customer")
                        ->where("enterprise",$enterpriseId)
                        ->where("is_deleted",0)
                        ->where("type","supplier")
                        ->where("debt_money",">",0)
                        ->sum("debt_money");
						
		$paidForSupplier = doubleval($paidForSupplier);
		return $paidForSupplier;
	}
    
    //新增用户(1,2,3,4,5)
    function addCustomer($arr){
        $rt = DB::table("base_customer")
                ->insert($arr);
        return $rt;
    }
    
    //新增分组
    function addCustomerGroup($arr){
        $rt = DB::table("base_customer_group")->insert($arr);
        return $rt;
    }
    
    //根据分组名查询
    function findGroupByName($enterpriseId,$groupName,$customerType){
        $rt = DB::table("base_customer_group")
                ->where("enterprise",$enterpriseId)
                ->where("group_name",$groupName)
                ->where("customer_type",$customerType)
                ->pluck("id");
        return $rt;
    }
    
    //根据电话查询客户是否存在
    function findCstomerByMoblie($enterpriseId,$mobile,$customerType){
        if($customerType == "customer"){
            $rt = DB::table("base_customer")
                    ->where("enterprise",$enterpriseId)
                    ->where("mobile",$mobile)
                    ->where("type","<>","supplier")
                    ->count();
        }else{
            $rt = DB::table("base_customer")
                    ->where("enterprise",$enterpriseId)
                    ->where("mobile",$mobile)
                    ->where("type","supplier")
                    ->count();
        }
        return $rt;
    }
    
    //根据客户ID查询金额
    function findMoneyByCustomerId($customerId){
        $money = DB::table('base_customer')
                   ->select('debt_money as debtMoney','trade_money_sum as sumTradeMoney')
                   ->where('id',$customerId)
                   ->where('is_deleted',0)
                   ->first();
        return $money;
    }
    
    //修改客户信息
    function updateCustomer($arr){
        //
        $rt = DB::table("base_customer")
                ->where("id",$arr["id"])
                ->update($arr);
        return $rt;
    }
    
    //客户手机号码唯一性
    function findMoblieIsExit($enterpriseId,$mobile,$customerId,$customerType){
        $rt = DB::table("base_customer")
                ->where("enterprise",$enterpriseId)
                ->where("mobile",$mobile)
                ->where("type",$customerType)
                ->where("id","<>",$customerId)
                ->count();
        return $rt;
    }
    
    //客户收款
    function updateDebtMoney($customerId,$modifyDate,$editor,$version,$lastReceiptDate,$debtMoney){
        $sql = "
                update `base_customer` set `modify_date` = ?,editor = ?,version = ?,last_receipt_date = ?,debt_money = debt_money - ? WHERE `id` = ? 
                ";
        $rt = DB::update($sql, [$modifyDate,$editor,$version,$lastReceiptDate,$debtMoney,$customerId]);
        return $rt;
    }
    
    //客户根据首字母分组列表
    function customerListByFirstWord($enterpriseId,$customerType,$keyword){
        $sql = "
                SELECT `id`,`name`,`debt_money` as `debtMoney`,`trade_money_sum` as `tradeMoneySum`,`mobile`,`group`,`type`,`remark`,`reserve1` as `discount`,`reserve2` as `groupId` 
                    FROM base_customer 
                    WHERE `enterprise` = '".$enterpriseId."'
                        and `is_deleted` = 0
                ";
        if($customerType == "customer"){
            $sql .= "and (`type` = 'wholesale' or `type` = 'retail')";
        }else{
            $sql .= "and `type` = 'supplier'";
        }
        if($keyword){
            $kw = changeStr($keyword);
            $sql .= "and `name` like '".$kw."'";
        }
        $sql .= " ORDER BY CONVERT( name USING gbk ) COLLATE gbk_chinese_ci ASC";
        $rt = DB::select($sql,[]);
        return $rt;
    }

    //根据电话查询客户是否存在
    public static function findCstomerInfo($enterpriseId,$mobile){
        $rt = DB::table("base_customer")
                ->where("enterprise",$enterpriseId)
                ->where("mobile",$mobile)
                ->where("type","<>","supplier")
                ->where('is_deleted',0)
                ->first();
        //print_r($rt);
        //echo "= = = = = == ".$enterpriseId.' '.$mobile;
        return $rt;
    }

    public static function saveCustomerGroup($createName,$groupVersion,$enterpriseId,$shopId,$group_name,$discount,$customerType){
        $arr = array();
        $id = get_newid("base_customer_group");
        $arr["id"] = $id;
        $arr["is_deleted"] = 0;
        $arr["create_date"] = get_ms();
        $arr["modify_date"] = get_ms();
        $arr["creator"] = $createName;
        $arr["editor"] = $createName;
        $arr["version"] = $groupVersion;//版本
        $arr["enterprise"] = $enterpriseId;
        $arr["shop"] = $shopId;
        $arr["group_name"] = $group_name;
        $arr["discount"] = $discount;
        $arr["customer_type"] = $customerType;
        $addGroup = EntryCustomer::addCustomerGroup($arr);
        return $id;
    }

    public static function saveCustomerInfo($enterpriseId,$shopId,$createName,$customer_name,$customer_mobile,$province,$city,$area,$address,$phone,$email,$group,$type,$debt_money,$remark){

        $time = get_ms();
        $maxVersion = max_version('base_customer',$enterpriseId) + 1;
        $id = get_newid('base_customer');
        $tmp = array();
        $tmp["id"] = $id;
        $tmp["is_deleted"] = 0;
        $tmp["create_date"] = $time;
        $tmp["modify_date"] = $time;
        $tmp["enterprise"] = $enterpriseId;
        $tmp["shop"] = $shopId;
        $tmp["creator"] = $createName;
        $tmp["editor"] = $createName;
        $tmp["operator"] = $createName;
        $tmp["version"] = $maxVersion;

        $tmp["reserve1"] = "100";
        
        $tmp["name"] = $customer_name;
        $tmp["mobile"] = $customer_mobile;
        $tmp["phone"] = $phone;
        $tmp["email"] = $email;
        $tmp["address"] = $province.$city.$area.$address;
        $groupName = $group ? $group : "默认分组";
        $tmp["group"] = $groupName;
        $tmp["type"] = $type;//"retail";
        
        $tmp["debt_money"] = $debt_money;
        $tmp["remark"] = $remark;
                
        $tmp["reserve2"] = null;
        
        $customerType = "customer";
        $getGroupId = EntryCustomer::findGroupByName($enterpriseId,$groupName,$customerType);
        //print_r($getGroupId);
        if($getGroupId){
            $tmp["reserve2"] = $getGroupId;
        }else{
            $groupVersion = max_version("base_customer_group",$enterpriseId)+1;
            $groupId = EntryCustomer::saveCustomerGroup($createName,$groupVersion,$enterpriseId,$shopId,$groupName,"100",$customerType);
            //echo "string ".$groupId;
            $tmp["reserve2"] = $groupId;
        }
        //print_r($tmp);
        $rt = DB::table("base_customer")
                ->insert($tmp);
        if($rt){
            return $id;
        }
        return null;
        
    }
    
    //根据电话查询客户是否存在
    function getCstomerByMoblie($enterpriseId,$mobile,$customerType){
        if($customerType == "customer"){
            $rt = DB::table("base_customer")
                    ->where("enterprise",$enterpriseId)
                    ->where("mobile",$mobile)
                    ->where("type","<>","supplier")
                    ->first();
        }else{
            $rt = DB::table("base_customer")
                    ->where("enterprise",$enterpriseId)
                    ->where("mobile",$mobile)
                    ->where("type","supplier")
                    ->first();
        }
        return $rt;
    }
    
    /** 
      * 函数名: customerStockListByTime
      * 用途: 客户最后进货时间列表
      *
      * @access public 
      * @param enterpriseId 企业Id
      * @param shopId 店铺Id
      * @param orderDate 订单时间点
      * @param offset 开始条数
      * @param rows 行数
      * @return array 
    */ 
    public function customerStockListByTime($enterpriseId,$shopId,$orderDate,$offset,$rows){
        $sql = "
                SELECT
                    `bc`.`id` AS `customerId`,
                    `bc`.`name` AS `customerName`,
                    IFNULL(`tmp_order`.`order_date`,'') AS `orderDate`
                FROM
                    `base_customer` AS `bc`
                LEFT JOIN (
                    SELECT
                        FROM_UNIXTIME(
                            `bo`.`order_date` / 1000,
                            '%Y-%m-%d %H:%i:%s'
                        ) AS `order_date`,
                        `bo`.`customer`
                    FROM
                        `biz_order` AS `bo`
                    WHERE
                        `bo`.`enterprise` = '".$enterpriseId."'
                    AND `bo`.`shop` = '".$shopId."'
                    AND `bo`.`is_return` = 0
                    AND `bo`.`is_deleted` = 0
                    AND `bo`.customer <> ''
                    GROUP BY
                        `bo`.`customer`
                    ORDER BY
                        `order_date` DESC
                ) AS `tmp_order` ON `bc`.`id` = `tmp_order`.`customer`
                WHERE
                    `bc`.`enterprise` = '".$enterpriseId."'
                AND `bc`.`shop` = '".$shopId."'
                AND `bc`.`is_deleted` = 0
                AND (
                    `bc`.`type` = 'retail'
                    OR `bc`.`type` = 'wholesale'
                )
                AND UNIX_TIMESTAMP(IFNULL(`tmp_order`.`order_date`,'2015-01-01 00:00:00'))*1000 < '".$orderDate."'
                ORDER BY
                    `orderDate` DESC
                LIMIT ".$rows."
                OFFSET ".$offset;
        $results = DB::select($sql, []);
        return $results;
    }
    
    /** 
      * 函数名: customerListByLimitDebtMoney
      * 用途: 客户欠款预警列表
      *
      * @access public 
      * @param enterpriseId 企业Id
      * @param offset 开始条数
      * @param rows 行数
      * @return array 
    */ 
    public function customerListByLimitDebtMoney($enterpriseId,$keyword,$limitDebtMoney,$offset,$rows){
        if($limitDebtMoney){
            $select = "id as customerId,name as customerName,mobile as customerMobile,debt_money as debtMoney,limit_debt_money as limitDebtMoney,if(limit_debt_money=0,".$limitDebtMoney.",limit_debt_money) - debt_money as surplusMoney";
        }else{
            $select = "id as customerId,name as customerName,mobile as customerMobile,debt_money as debtMoney,limit_debt_money as limitDebtMoney,limit_debt_money - debt_money as surplusMoney";
        }
        $query = DB::table("base_customer")
                   ->select(DB::raw($select))
                   ->where("enterprise",$enterpriseId)
                   ->where("is_deleted",0);
        if($keyword){
            $this->kw = "%".$keyword."%";
            $query->where(
                        function($sql){
                            $sql->where("name","like",$this->kw)
                                ->orWhere("mobile","like",$this->kw);
                        }
                    );
        }
        $rt = $query->orderBy("surplusMoney",'asc')
                    ->orderBy("modify_date",'desc')
                    ->skip($offset)
                    ->take($rows)
                    ->get();
        return $rt;
    }
    
    /** 
      * 函数名: customerGoodsRelationList
      * 用途: 客户商品关系列表数据请求
      *
      * @access public 
      * @param enterpriseId 企业Id
      * @param shopId 店铺Id
      * @param orderDate 订单时间点
      * @param offset 开始条数
      * @param rows 行数
      * @return array 
    */ 
    public function customerGoodsRelationList($enterpriseId,$shopId,$keyword,$offset,$rows){
        $sql = "select 
                    `tmp_bcgr`.`relarion_id` as `relarionId`,
                    `tmp_bcgr`.`enterprise_id` as `enterpriseId`,
                    `tmp_bcgr`.`customer_id` as `customerId`,
                    `tmp_bcgr`.`customer_name` as `customerName`,
                    `tmp_bcgr`.`goods_id` as `goodsId`,
                    IFNULL(`tmp_bcgr`.`dimensional_id`,'') as `dimensionalId`,
                    `tmp_bcgr`.`goods_name` as `goodsName`,
                    `tmp_bcgr`.`property_value1` as `propertyValue1`,
                    `tmp_bcgr`.`property_value2` as `propertyValue2`,
                    IFNULL(FROM_UNIXTIME(`tmp_order`.`order_date` / 1000,'%Y-%m-%d %H:%i:%s'),'') as `orderDate`,
                    `tmp_bcgr`.`tip_days` as `tipDays`,
                    DATEDIFF(NOW(),FROM_UNIXTIME(`tmp_order`.`order_date` / 1000,'%Y-%m-%d %H:%i:%s')) as `dayNum`,
                    (`tmp_bcgr`.`tip_days` - DATEDIFF(NOW(),FROM_UNIXTIME(`tmp_order`.`order_date` / 1000,'%Y-%m-%d %H:%i:%s'))) as  `differDayNum`
                from (
                    select 
                        `bcgr`.`id` as `relarion_id`,
                        `bcgr`.`enterprise` as `enterprise_id`,
                        `bcgr`.`customer` as `customer_id`,
                        `bc`.`name` as `customer_name`,
                        `bcgr`.`goods` as `goods_id`,
                        IFNULL(`bcgr`.`dimensional_id`,'') as `dimensional_id`,
                        `tmp_goods`.`goods_name` as `goods_name`,
                        `tmp_goods`.`property_value1` as `property_value1`,
                        `tmp_goods`.`property_value2` as `property_value2`,
                        `bcgr`.`tip_days` 
                    from `biz_customer_goods_relation` as `bcgr`
                    left join `base_customer` as `bc` on `bcgr`.`customer` = `bc`.`id`
                    left join(
                        select
                            `bg`.`id` AS `tmp_goods_goods_id`,
                            IFNULL(`bgd`.`id`, '') AS `tmp_goods_dimensional_id`,
                            `bg`.`name` AS `goods_name`,
                            IFNULL(`bgd`.`property_value1`, '') AS `property_value1`,
                            IFNULL(`bgd`.`property_value2`, '') AS `property_value2`
                        from
                            `base_goods` AS `bg`
                        left join `base_goods_dimensional` AS `bgd` ON `bg`.`id` = `bgd`.`goods`
                        and `bgd`.`is_deleted` = 0
                        where `bg`.`enterprise` = '".$enterpriseId."'
                          and `bg`.`is_deleted` = 0
                    ) as `tmp_goods` on `bcgr`.`goods` = `tmp_goods`.`tmp_goods_goods_id` and IFNULL(`bcgr`.`dimensional_id`,'') = `tmp_goods`.`tmp_goods_dimensional_id`
                    where `bcgr`.`enterprise` = '".$enterpriseId."'
                      and `bcgr`.`shop` = '".$shopId."'
                      and `bcgr`.`is_deleted` = 0
                    order by `bcgr`.`customer`
                ) as `tmp_bcgr`
                left join (
                    select 
                        `bo`.`customer` as `customer_id`,
                        `bo`.`order_date`,
                        `bod`.`goods` as `goods_id`,
                        IFNULL(`bod`.`dimensional_id`,'') as `dimensional_id`
                    from `biz_order_dtl` as `bod`
                    inner join `biz_order` as `bo` on `bod`.`order` = `bo`.`id`
                    where `bod`.`enterprise` = '".$enterpriseId."'
                      and `bod`.`is_deleted` = 0
                      and `bo`.`customer` is not null
                      and `bo`.`customer` <> ''
                    group by `bo`.`customer`,`bod`.`goods`,`bod`.`dimensional_id`
                    order by `bo`.`order_date`
                ) as `tmp_order` on `tmp_bcgr`.`customer_id` = `tmp_order`.`customer_id`
                                  and `tmp_bcgr`.`goods_id` = `tmp_order`.`goods_id`
                                  and `tmp_bcgr`.`dimensional_id` = `tmp_order`.`dimensional_id`
                ";
        if($keyword){
            $sql .= "where `tmp_bcgr`.`customer_name` like '%".$keyword."%'";
        }
        $sql .= "order by differDayNum limit ".$rows." offset ".$offset;
        $results = DB::select($sql, []);
        return $results;
    }
}