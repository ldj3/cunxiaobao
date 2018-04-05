<!--div class="sidebar-shortcuts" id="sidebar-shortcuts">
    <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
        <button class="btn btn-success">
            <i class="icon-signal"></i>
        </button>

        <button class="btn btn-info">
            <i class="icon-pencil"></i>
        </button>

        <button class="btn btn-warning">
            <i class="icon-group"></i>
        </button>

        <button class="btn btn-danger">
            <i class="icon-cogs"></i>
        </button>
    </div>

    <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
        <span class="btn btn-success"></span>

        <span class="btn btn-info"></span>

        <span class="btn btn-warning"></span>

        <span class="btn btn-danger"></span>
    </div>
</div>< #sidebar-shortcuts -->

<ul class="nav nav-list">
    <li class="active">
        <a href="{{ url('/special/index') }}" menu_id="1">
            <i class="icon-dashboard"></i>
            <span class="menu-text">首页</span>
        </a>
    </li>
    <li>
        <a href="#" class="dropdown-toggle" menu_id="2">
            <i class="icon-edit"></i>
            <span class="menu-text">商品管理</span>

            <b class="arrow icon-angle-down"></b>
        </a>
        <ul class="submenu">
            <li>
                <a href="{{ url('/special/goods?menu_id=2-1') }}" menu_id="2-1">
                    <i class="icon-double-angle-right"></i>商品列表
                </a>
            </li>
            <li>
                <a href="{{ url('/special/goods/append?menu_id=2-5') }}" menu_id="2-5">
                    <i class="icon-double-angle-right"></i>增加商品
                </a>
            </li>
            <li>
                <a href="{{ url('/special/unit/unitGroup?menu_id=2-6') }}" menu_id="2-6">
                    <i class="icon-double-angle-right"></i>单位分组列表
                </a>
            </li>
            <li>
                <a href="{{ url('/special/goods/betchadd?menu_id=2-2') }}" menu_id="2-2">
                    <i class="icon-double-angle-right"></i>批量添加
                </a>
            </li>
            <li>
                <a href="{{ url('/special/goods/dimensionalBetchadd?menu_id=2-7') }}" menu_id="2-7">
                    <i class="icon-double-angle-right"></i>二维批量添加
                </a>
            </li>
            <li>
                <a href="{{ url('/special/goods/betchlog?menu_id=2-3') }}" menu_id="2-3">
                    <i class="icon-double-angle-right"></i>批量操作记录
                </a>
            </li>
            <li>
                <a href="{{ url('/special/storage/inout?menu_id=2-4') }}" menu_id="2-4">
                    <i class="icon-double-angle-right"></i>出入库流水
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="#" class="dropdown-toggle" menu_id="3">
            <i class="icon-list"></i>
            <span class="menu-text">订单管理</span>

            <b class="arrow icon-angle-down"></b>
        </a>
        <ul class="submenu">
            <li>
                <a href="{{ url('/special/order?menu_id=3-1') }}" menu_id="3-1">
                    <i class="icon-double-angle-right"></i>订单列表
                </a>
            </li>
            <li>
                <a href="{{ url('/special/refund?menu_id=3-2') }}" menu_id="3-2">
                    <i class="icon-double-angle-right"></i>退货单列表
                </a>
            </li>
			<li>
                <a href="{{ url('/special/purc?menu_id=3-4') }}" menu_id="3-4">
                    <i class="icon-double-angle-right"></i>采购单列表
                </a>
            </li>
            <li>
                <a href="{{ url('/special/purcBack?menu_id=3-5') }}" menu_id="3-5">
                    <i class="icon-double-angle-right"></i>采购退货单列表
                </a>
            </li>
            <li>
                <a href="{{ url('/special/order/inwarehouse?menu_id=3-6') }}" menu_id="3-6">
                    <i class="icon-double-angle-right"></i>入库订单列表
                </a>
            </li>
            <li>
                <a href="{{ url('/special/order/outwarehouse?menu_id=3-7') }}" menu_id="3-7">
                    <i class="icon-double-angle-right"></i>出库订单列表
                </a>
            </li>
            <li>
                <a href="{{ url('/special/bill/receipt?menu_id=3-3') }}" menu_id="3-3">
                    <i class="icon-double-angle-right"></i>收款列表
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="#" class="dropdown-toggle" menu_id="8">
            <i class="icon-exchange"></i>
            <span class="menu-text">仓库管理</span>

            <b class="arrow icon-angle-down"></b>
        </a>
        <ul class="submenu">
            <li>
                <a href="{{ url('/special/warehouse/allocationList?menu_id=8-3') }}" menu_id="8-3">
                    <i class="icon-double-angle-right"></i>调拨仓库列表
                </a>
            </li>
            <li>
                <a href="{{ url('/special/warehouse?menu_id=8-2') }}" menu_id="8-2">
                    <i class="icon-double-angle-right"></i>仓库列表
                </a>
            </li>
            <li>
                <a href="{{ url('/special/warehouse/warehouseAdd?menu_id=8-1') }}" menu_id="8-1">
                    <i class="icon-double-angle-right"></i>新增仓库
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="#" class="dropdown-toggle" menu_id="9">
            <i class="icon-desktop"></i>
            <span class="menu-text">销售统计</span>
            <b class="arrow icon-angle-down"></b>
        </a>
        <ul class="submenu">
            <li>
                <a href="{{ url('/special/sales/daySales?menu_id=9-1') }}" menu_id="9-1">
                    <i class="icon-double-angle-right"></i>日销售
                </a>
            </li>
            <li>
                <a href="{{ url('/special/sales/monthSales?menu_id=9-2') }}" menu_id="9-2">
                    <i class="icon-double-angle-right"></i>月销售
                </a>
            </li>
            <li>
                <a href="{{ url('/special/sales/daySalesInOut?menu_id=9-3') }}" menu_id="9-3">
                    <i class="icon-double-angle-right"></i>日销售出入库流水
                </a>
            </li>
            <li>
                <a href="{{ url('/special/sales/monthSalesInOut?menu_id=9-4') }}" menu_id="9-4">
                    <i class="icon-double-angle-right"></i>月销售出入库流水
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="#" class="dropdown-toggle" menu_id="4">
            <i class="icon-edit"></i>
            <span class="menu-text">店铺管理</span>

            <b class="arrow icon-angle-down"></b>
        </a>
        <ul class="submenu">
            <li>
                <a href="{{ url('/special/shop?menu_id=4-1') }}" menu_id="4-1">
                    <i class="icon-double-angle-right"></i>店铺列表
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="{{ url('/special/customer') }}" class="dropdown-toggle" menu_id="5">
            <i class="icon-list-alt"></i>
            <span class="menu-text">客户管理</span>

            <b class="arrow icon-angle-down"></b>
        </a>
        <ul class="submenu">
            <li>
                <a href="{{ url('/special/customer?menu_id=5-1') }}" menu_id="5-1">
                    <i class="icon-double-angle-right"></i>客户列表
                </a>
            </li>
            <li>
                <a href="{{ url('/special/customer/add?menu_id=5-3') }}" menu_id="5-3">
                    <i class="icon-double-angle-right"></i>添加客户
                </a>
            </li>
            <li>
                <a href="{{ url('/special/customer/suppliers?menu_id=5-4') }}" menu_id="5-4">
                    <i class="icon-double-angle-right"></i>供应商列表
                </a>
            </li>
            <li>
                <a href="{{ url('/special/customer/supplierAdd?menu_id=5-5') }}" menu_id="5-5">
                    <i class="icon-double-angle-right"></i>添加供应商
                </a>
            </li>
            <li>
                <a href="{{ url('/special/customer/betchadd?menu_id=5-2') }}" menu_id="5-2">
                    <i class="icon-double-angle-right"></i>批量添加
                </a>
            </li>
            <li>
                <a href="{{ url('/special/customer/customerGroupList?menu_id=5-6') }}" menu_id="5-6">
                    <i class="icon-double-angle-right"></i>客户分组列表
                </a>
            </li>
            <li>
                <a href="{{ url('/special/customer/supplierGroupList?menu_id=5-7') }}" menu_id="5-7">
                    <i class="icon-double-angle-right"></i>供应商分组列表
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="{{ url('/special/print?menu_id=7') }}" class="dropdown-toggle" menu_id="7">
            <i class="icon-laptop"></i>
            <span class="menu-text">打印设置</span>

            <b class="arrow icon-angle-down"></b>
        </a>
    </li>
    <li>
        <a href="#" class="dropdown-toggle" menu_id="6">
            <i class="icon-tag"></i>
            <span class="menu-text">其他设置</span>

            <b class="arrow icon-angle-down"></b>
        </a>
        <ul class="submenu">
            <li>
                <a href="{{ url('/special/resetspwd') }}" menu_id="6-1">
                    <i class="icon-double-angle-right"></i>修改密码
                </a>
            </li>
            @if(Session::get("setterPermits") == -1)
            <li>
                <a href="{{ url('/special/setting?menu_id=6-2') }}" menu_id="6-2">
                    <i class="icon-double-angle-right"></i>帐号设置
                </a>
            </li>
            @endif
        </ul>
    </li>
    
</ul><!-- /.nav-list -->
<script>
$(function(){
    var menu_id = $("#menu_id").val();
    var ms = menu_id.split("-");
    //alert(menu_id+" "+ms[0]+" "+ms[1]);
    if(ms.length == 1)
    {
        $("a[menu_id="+menu_id+"]").parent("li").addClass("active");
    }
    else if(ms.length == 2)
    {
        $("a[menu_id="+menu_id+"]").parent("li").addClass("active");
        $("a[menu_id="+ms[0]+"]").parent("li").addClass("active open");
    }
})
</script>