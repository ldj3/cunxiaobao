<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>存销宝</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- basic styles -->
    <link href="{{ asset('/ace/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/ace/css/colorbox.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('/ace/css/font-awesome.min.css') }}" />
    <!--[if IE 7]>
    <link rel="stylesheet" href="{{ asset('/ace/css/font-awesome-ie7.min.css') }}" />
    <![endif]-->
    <!-- page specific plugin styles -->
    <!-- fonts -->
    <!-- ace styles -->
    <link rel="stylesheet" href="{{ asset('/ace/css/ace.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/ace/css/ace-rtl.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/ace/css/ace-skins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/ace/css/datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('/ace/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/ace/css/jquery-ui-1.10.3.full.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('/ace/css/chosen.css') }}" />
    <link rel="stylesheet" href="{{ asset('/ace/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('/ace/css/bootstrap-timepicker.css') }}"/>
    <style type="text/css">
        body {
            overflow: hidden;
        }
        frame{
            overflow: hidden;
        }
        .main-container{
            height:100%!important;
        }
        .tab-content {
            border: none;
            padding: 10px 0px;
            min-height:100%
        }
        #menu_li_0 {
            display: none;
        }
    </style>
</head>
<body class="navbar-fixed skin-1">
<div class="navbar navbar-fixed-top navbar-default" id="navbar">
    <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
    </script>
    <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
            <a href="#" class="navbar-brand">
                <small>
                    <!--i class="icon-leaf"></i-->
                    存销宝客户管理后台
                </small>
            </a><!-- /.brand -->
        </div><!-- /.navbar-header -->

        <div class="navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <!--img class="nav-user-photo" src="assets/avatars/user.jpg" alt="Jason's Photo" /-->
                        <span class="user-info">
                        {{Session::get("mobile")}}
                        </span>
                        <i class="icon-caret-down"></i>
                    </a>

                    <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <!--li>
                            <a href="#"><i class="icon-cog"></i>Settings
                            </a>
                        </li>
                        <li>
                            <a href="#"><i class="icon-user"></i>Profile</a>
                        </li>
                        <li class="divider"></li-->
                        <li>
                            <a href="{{url('/special/center')}}"><i class="icon-user"></i>个人中心</a>
                        </li>
                        <li>
                            <a href="{{url('/backstage/logout')}}"><i class="icon-off"></i>退出</a>
                        </li>
                    </ul>
                </li>
            </ul><!-- /.ace-nav -->
        </div><!-- /.navbar-header -->
    </div><!-- /.container -->
</div>
<div class="main-container" id="main-container">
    <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
    </script>
    <div class="main-container-inner">
        <a class="menu-toggler" id="menu-toggler" href="#">
            <span class="menu-text"></span>
        </a>
        <div class="sidebar fixed" id="sidebar">
            <script type="text/javascript">
                try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
            </script>
            
            <ul class="nav nav-list" id="menu">
                    
            </ul><!-- /.nav-list -->
            
            <div class="sidebar-collapse" id="sidebar-collapse">
                <i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
            </div>

            <script type="text/javascript">
                try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
            </script>
        </div>
        <div class="main-content">
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->
                        <ul class="nav nav-tabs" role="tablist">
                            <!--<li class="active"><a href="#Index" role="tab" data-toggle="tab">首页</a></li>-->
                        </ul>
                        <div class="tab-content">
                            <!--<div role="tabpanel" class="tab-pane active" id="Index"></div>-->
                        </div>
                        <!-- PAGE CONTENT ENDS -->
                    </div><!-- /.col -->
                </div><!-- /.row
            </div><!-- /.page-content -->
            
        
        </div><!-- /.main-content -->
        
        
    </div><!-- /.main-container-inner -->

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
    <i class="icon-double-angle-up icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->
    
        
    <!--[if !IE]> -->
    <script type="text/javascript">

        window.jQuery || document.write("<script src='"+"{{asset('/ace/js/jquery-2.0.3.min.js')}}"+"'>"+"<"+"script>");
    </script>
    <!-- <![endif]-->
    <!--[if lte IE 8]>
      <link rel="stylesheet" href="{{ asset('/ace/css/ace-ie.min.css') }}" />
    <![endif]-->
    <!-- inline styles related to this page -->
    <!-- ace settings handler -->
    <script src="{{ asset('/js/jquery-1.11.3.min.js') }}"></script>
    <script src="{{ asset('/ace/js/ace-extra.min.js') }}"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
    if("ontouchend" in document) document.write("<script src='<?php echo asset('/ace/js/jquery.mobile.custom.min.js');?>'>"+"<"+"/script>");
    </script>
    
    <script src="{{ asset('/ace/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/ace/js/jquery-ui-1.10.3.full.min.js') }}"></script>
    <script src="{{ asset('/ace/js/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('/ace/js/typeahead-bs2.min.js') }}"></script>
    <script src="{{ asset('/ace/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('/ace/js/ace.min.js') }}"></script>
    <script src="{{ asset('/ace/js/fuelux/fuelux.spinner.min.js') }}"></script>
    
    <script src="{{ asset('/ace/js/fuelux/fuelux.wizard.min.js') }}"></script>
    <script src="{{ asset('/ace/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/ace/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('/ace/js/bootbox.min.js') }}"></script>
    <script src="{{ asset('/ace/js/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ asset('/ace/js/select2.min.js') }}"></script>
    <script src="{{ asset('/js/app.js') }}"></script>
    
    <!-- 自定义js -->
    <script src="{{ asset('/ace/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('/ace/js/bootstrap-tab.js') }}"></script>
    <script type="text/javascript">

        $(function () {
            $(".tab-content").css('height', (document.body.scrollHeight-100)+'px');
            $("#sidebar").height($(window).height()-$("#navbar").height()-40);      
            $('#menu').sidebarMenu({
                data: [
                    {
                        id: '0',
                        text: '首页',
                        icon: 'icon-glass',
                        close: false,
                        url: '../ace/html/index.html?ttt=1'
                    },
                    {
                        id: '1',
                        text: '商品管理',
                        icon: 'icon-edit',
                        url: '',
                        menus: [
                            {
                                id: '11',
                                text: '商品列表',
                                icon: 'icon-glass',
                                url: '/backstage/goodsListIndex'
                            },
                            {
                                id: '12',
                                text: '添加商品',
                                icon: 'icon-glass',
                                url: '../ace/html/addGoods.html'
                            },
                            {
                                id: '13',
                                text: '单位分组列表',
                                icon: 'icon-glass',
                                url: '../ace/html/goodsGroupList.html'
                            }
                        ]
                    },
                    {
                        id: '2',
                        text: '基础数据',
                        icon: 'icon-leaf',
                        url: '',
                        menus: [
                            {
                                id: '21',
                                text: 'jqgrid表单',
                                icon: 'icon-glass',
                                url: '/backstage/index'
                            },
                            {
                                id: '22',
                                text: 'treeview',
                                icon: 'icon-glass',
                                url: 'treeview.html'
                            },
                            {
                                id: '23',
                                text: '物料维护',
                                icon: 'icon-glass',
                                url: '/Model/Index'
                            },
                            {
                                id: '24',
                                text: '站点管理',
                                icon: 'icon-glass',
                                url: '/Station/Index'
                            }
                        ]
                    },
                    {
                        id: 'Order',
                        text: '订单管理',
                        icon: 'icon-leaf',
                        url: '',
                        menus: [
                            {
                                id: 'SaleOrderList',
                                text: '销售订单',
                                icon: 'icon-glass',
                                url: '/Order/SaleOrderList'
                            },
                            {
                                id: 'PurchaseOrderList',
                                text: '采购订单',
                                icon: 'icon-glass',
                                url: '/Order/PurchaseOrderList'
                            },
                            {
                                id: 'ReturnSaleOrderList',
                                text: '退货订单',
                                icon: 'icon-glass',
                                url: '/Order/ReturnSaleOrderList'
                            },
                            {
                                id: 'ReturnPurchaseOrderList',
                                text: '退采购订单',
                                icon: 'icon-glass',
                                url: '/Order/ReturnPurchaseOrderList'
                            },
                            {
                                id: 'CheckOrderList',
                                text: '仓库盘点单',
                                icon: 'icon-glass',
                                url: '/Order/CheckOrderList'
                            },
                            {
                                id: 'WarehouseAllocationOrderList',
                                text: '仓库调拨单',
                                icon: 'icon-glass',
                                url: '/Order/WarehouseAllocationOrderList'
                            },
                            {
                                id: 'OutOrderList',
                                text: '其他出库单',
                                icon: 'icon-glass',
                                url: '/Order/OutOrderList'
                            },
                            {
                                id: 'InOrderList',
                                text: '其他入库单',
                                icon: 'icon-glass',
                                url: '/Order/InOrderList'
                            },
                            {
                                id: 'ReceivablesList',
                                text: '收款列表',
                                icon: 'icon-glass',
                                url: '/Order/ReceivablesList'
                            }
                        ]
                    },
                    {
                        id: 'Customer',
                        text: '客户/供应商',
                        icon: 'icon-edit',
                        url: '',
                        menus: [
                            {
                                id: 'CustomerList',
                                text: '客户列表',
                                icon: 'icon-glass',
                                url: '/Customer/CustomerList'
                            },
                            {
                                id: 'SupplierList',
                                text: '供应商列表',
                                icon: 'icon-glass',
                                url: '/Customer/SupplierList'
                            },
                            {
                                id: 'CustomerGroupList',
                                text: '客户分组',
                                icon: 'icon-glass',
                                url: '/Customer/CustomerGroupList'
                            },
                            {
                                id: 'SupplierGroupList',
                                text: '供应商分组',
                                icon: 'icon-glass',
                                url: '/Customer/SupplierGroupList'
                            },
                        ]
                    },
                    {
                        id: 'Report',
                        text: '店铺报表',
                        icon: 'icon-edit',
                        url: '',
                        menus: [
                            {
                                id: 'Report-day',
                                text: '日报表',
                                icon: 'icon-glass',
                                url: '/backstage/goodsListIndex'
                            },
                            {
                                id: 'Report-month',
                                text: '月报表',
                                icon: 'icon-glass',
                                url: '/Report/'
                            },
                            {
                                id: 'Transaction',
                                text: '交易流水',
                                icon: 'icon-glass',
                                url: '/Report/Transaction'
                            },
                        ]
                    },
                    {
                        id: 'Setter',
                        text: '基础设置',
                        icon: 'icon-edit',
                        url: '',
                        menus: [
                            {
                                id: 'ShopSetterList',
                                text: '店铺列表',
                                icon: 'icon-glass',
                                url: '/Setter/ShopSetterList'
                            },
                            {
                                id: 'WarehouseSetterList',
                                text: '仓库列表',
                                icon: 'icon-glass',
                                url: '/Setter/WarehouseSetterList'
                            },
                            {
                                id: 'UpdatePassWord',
                                text: '修改密码',
                                icon: 'icon-glass',
                                url: '/Setter/UpdatePassWord'
                            },
                            {
                                id: 'ReportSetter',
                                text: '报表推送',
                                icon: 'icon-glass',
                                url: '/Setter/ReportSetter'
                            },
                            {
                                id: 'PrintSetter',
                                text: '打印设置',
                                icon: 'icon-glass',
                                url: '/Setter/PrintSetter'
                            },
                        ]
                    }
                ]
            });
            eval($("#menu_li_0 a").attr("href"));
        });
    </script>
    
</body>
</html>