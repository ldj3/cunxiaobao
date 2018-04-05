<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <link href="/ace/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="/ace/css/font-awesome.min.css" />

        <!--[if IE 7]>
        <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
    <![endif]-->

        <!-- page specific plugin styles -->
        <!-- ace styles -->
        <link rel="stylesheet" href="/ace/css/jquery-ui-1.10.3.full.min.css" />
        <link rel="stylesheet" href="/ace/css/ui.jqgrid.css" />
        <link rel="stylesheet" href="/ace/css/ace.min.css" />
        <link rel="stylesheet" href="/ace/css/ace-rtl.min.css" />
        <link rel="stylesheet" href="/ace/css/ace-skins.min.css" />

        <!--[if lte IE 8]>
        <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
    <![endif]-->

        <!-- inline styles related to this page -->

        <!-- ace settings handler -->
    </head>

    <body>
        <div class="page-content">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12">
                        <table id="grid-table"></table>

                        <div id="grid-pager"></div>
                        <script type="text/javascript">
                            var $path_base = "/"; //this will be used in gritter alerts containing images
                        </script>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.page-content -->

        <style type="text/css">
            body {
                background-color: #FFFFFF;
            }
        </style>
        <!-- basic scripts -->

        <script type="text/javascript">
            window.jQuery || document.write("<script src='/ace/js/jquery-2.0.3.min.js'>" + "<" + "script>");
        </script>

        <script src="/ace/js/ace-extra.min.js"></script>
        <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <script src="assets/js/respond.min.js"></script>
<![endif]-->

        <!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='/ace/js/jquery-1.10.2.min.js'>"+"<"+"script>");
</script>
<![endif]-->

        <script type="text/javascript">
            if("ontouchend" in document) document.write("<script src='/ace/js/jquery.mobile.custom.min.js'>" + "<" + "script>");
        </script>

        <script src='/ace/js/jquery-2.0.3.min.js'></script>

        <!-- 自定义js -->
        <script src="/ace/js/sidebar-menu.js"></script>
        <script src="/ace/js/bootstrap-tab.js"></script>

        <script src="/ace/js/bootstrap.min.js"></script>
        <script src="/ace/js/typeahead-bs2.min.js"></script>
        <!-- page specific plugin scripts -->

        <!--[if lte IE 8]>
    <script src="/ace/js/excanvas.min.js"></script>
<![endif]-->

        <script src="/ace/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="/ace/js/jquery.ui.touch-punch.min.js"></script>
        <script src="/ace/js/jquery.slimscroll.min.js"></script>
        <script src="/ace/js/jquery.easy-pie-chart.min.js"></script>
        <script src="/ace/js/jquery.sparkline.min.js"></script>
        <script src="/ace/js/flot/jquery.flot.min.js"></script>
        <script src="/ace/js/flot/jquery.flot.pie.min.js"></script>
        <script src="/ace/js/flot/jquery.flot.resize.min.js"></script>

        <!-- ace scripts -->

        <script src="/ace/js/ace-elements.min.js"></script>
        <script src="/ace/js/ace.min.js"></script>

        <script src="/ace/js/jqGrid/jquery.jqGrid.min.js"></script>
        <script src="/ace/js/jqGrid/grid.locale-cn.js"></script>
        <!-- inline scripts related to this page -->
        <script type="text/javascript">
            function download(second) {
                countDown(second);
                var url = '/special/goods/download';
                var query = "";
                var keyword = $("#keyword").val();
                var goods_barcode = $("#goods_barcode").val();
                query += "keyword=" + keyword + "&goods_barcode=" + goods_barcode;
                window.location.href = url + "?" + query;
            }

            function countDown(second) {
                obj = $("[class='btn btn-sm btn-primary out']");
                if(second >= 0) // 如果秒数还是大于0，则表示倒计时还没结束
                {
                    if(typeof buttonDefaultValue === 'undefined') {
                        buttonDefaultValue = obj.text(); // 获取默认按钮上的文字
                    }
                    obj.attr("disabled", "true"); // 按钮置为不可点击状态
                    obj.text("下载中..." + '(' + second + ')'); // 按钮里的内容呈现倒计时状态
                    second--; // 时间减一
                    setTimeout(function() {
                        countDown(second);
                    }, 1000); // 一秒后重复执行
                } else // 否则，按钮重置为初始状态
                {
                    obj.removeAttr("disabled"); // 按钮置未可点击状态
                    obj.text(buttonDefaultValue); // 按钮里的内容恢复初始状态
                }
            }
            //商品删除
            function del(obj) {
                if(confirm("确定要删除该商品么?")) {
                    var url = '/special/goods/del';
                    var query = "goodsId=" + obj;
                    $.post(url, query, function(data) {
                        alert(data.msg);
                        if(data.status) {
                            $(".close").click();
                            ajaxpage($("#page").val());
                        } else {
                            return false;
                        }
                    });
                }
            }
            
            $(function(){

                var navList = [' ', '名称','批发价', '零售价', '成本价', '属性一', '属性二', '属性三', '库存','商品条形码'];
                var url = '/backstage/shop/getShopAttribute';
                var obj = new Object;
                obj.page = 1;
                $.post(url, obj, function(data) {
                    navList[5] = data.data.goodsPropertyOne;
                    navList[6] = data.data.goodsPropertyTwo;
                    navList[7] = data.data.goodsPropertyThree;
            
                    var grid_selector = "#grid-table";
                    var pager_selector = "#grid-pager";
                    jQuery(grid_selector).jqGrid({
                        //direction: "rtl",
                        
                        //datatype: "local",
                        url: '/backstage/goods/search',
                        mtype: "POST",
                        datatype: 'json',
                        height: 400,
                        colNames: navList,
                        colModel: [
                            {
                                name: 'id',
                                index: 'id',
                                width: 50,
                                fixed: true,
                                sortable: false,
                                resize: false,
                                formatter: 'actions',
                                formatoptions: {
                                    keys: true,
                                    editbutton:false,
                                    delOptions: {
                                        url: '/backstage/goods/del',
                                        mtype: 'POST',
                                        recreateForm: true,
                                        beforeShowForm: beforeDeleteCallback
                                    },
                                    //editformbutton:true, editOptions:{recreateForm: true, beforeShowForm:beforeEditCallback}
                                },
                                search:false
                            }, {
                                name: 'goodsName',
                                index: 'goodsName',
                                width: 150,
                                editable: false,
                                editoptions: {
                                    size: "20",
                                    maxlength: "30"
                                },
                                search:true,
                                searchoptions:{
                                    sopt:['eq']
                                }
                            }, {
                                name: 'tradePrice',
                                index: 'tradePrice',
                                width: 90,
                                editable: true,
                                editoptions: {
                                    size: "20",
                                    maxlength: "30"
                                },
                                search:false
                            }, {
                                name: 'retailPrice',
                                index: 'retailPrice',
                                width: 150,
                                editable: true,
                                editoptions: {
                                    size: "20",
                                    maxlength: "30"
                                },
                                search:false
                            }, {
                                name: 'costPrice',
                                index: 'costPrice',
                                width: 150,
                                editable: true,
                                editoptions: {
                                    size: "20",
                                    maxlength: "30"
                                },
                                search:false
                            }, {
                                name: 'property1',
                                index: 'property1',
                                width: 150,
                                editable: true,
                                editoptions: {
                                    size: "20",
                                    maxlength: "30"
                                },
                                search:false
                            }, {
                                name: 'property2',
                                index: 'property2',
                                width: 150,
                                editable: true,
                                editoptions: {
                                    size: "20",
                                    maxlength: "30"
                                },
                                search:false
                            }, {
                                name: 'property3',
                                index: 'property3',
                                width: 150,
                                editable: true,
                                editoptions: {
                                    size: "20",
                                    maxlength: "30"
                                },
                                search:false
                            }, {
                                name: 'totalQty',
                                index: 'totalQty',
                                width: 150,
                                editable: false,
                                editoptions: {
                                    size: "20",
                                    maxlength: "30"
                                },
                                search:false
                            }, {
                                name: 'barcode',
                                index: 'barcode',
                                width: 150,
                                editable: false,
                                editoptions: {
                                    size: "20",
                                    maxlength: "30"
                                },
                                search:false
                            }
                        ],

                        sortname : 'goodsId',
                        viewrecords: true,
                        rowNum: 10,
                        rowList: [10,20,30],
                        pager: pager_selector,
                        
                        altRows: true,
                        //toppager: true,

                        //multiselect: true,
                        //multikey: "ctrlKey",
                        //multiboxonly: true,

                        loadComplete: function() {
                            var table = this;
                            setTimeout(function() {
                                styleCheckbox(table);

                                updateActionIcons(table);
                                updatePagerIcons(table);
                                enableTooltips(table);
                            }, 0);
                        },
                        
                        editurl: "", //nothing is saved
                        caption: "",

                        autowidth: true,
                        serializeGridData: function(postData) {
                            //var data = JSON.stringify(postData);
                            //alert(data);
                            console.log(postData);
                            return postData;
                        },

                    });
                
                    //enable search/filter toolbar
                    //jQuery(grid_selector).jqGrid('filterToolbar',{defaultSearch:true,stringResult:true})

                    //switch element when editing inline
                    function aceSwitch(cellvalue, options, cell) {
                        setTimeout(function() {
                            $(cell).find('input[type=checkbox]')
                                .wrap('<label class="inline" />')
                                .addClass('ace ace-switch ace-switch-5')
                                .after('<span class="lbl"></span>');
                        }, 0);
                    }
                    //enable datepicker
                    function pickDate(cellvalue, options, cell) {
                        setTimeout(function() {
                            $(cell).find('input[type=text]')
                                .datepicker({
                                    format: 'yyyy-mm-dd',
                                    autoclose: true
                                });
                        }, 0);
                    }

                    //navButtons
                    jQuery(grid_selector).jqGrid(
                        'navGrid', 
                        pager_selector, 
                        { //navbar options
                            edit: false,
                            editicon: 'icon-pencil blue',
                            add: false,
                            addicon: 'icon-plus-sign purple',
                            del: false,
                            delicon: 'icon-trash red',
                            search: true,
                            searchicon: 'icon-search orange',
                            refresh: true,
                            refreshicon: 'icon-refresh green',
                            view: false,
                            viewicon: 'icon-zoom-in grey',
                        },
                        {
                            //edit record form
                            //closeAfterEdit: true,
                            recreateForm: true,
                            beforeShowForm: function(e) {
                                var form = $(e[0]);
                                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                                style_edit_form(form);
                            }
                        },
                        {
                            //new record form
                            closeAfterAdd: true,
                            recreateForm: true,
                            viewPagerButtons: false,
                            beforeShowForm: function(e) {
                                var form = $(e[0]);
                                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                                style_edit_form(form);
                            }
                        }, 
                        {
                            //delete record form
                            recreateForm: true,
                            beforeShowForm: function(e) {
                                var form = $(e[0]);
                                if(form.data('styled')) return false;

                                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                                style_delete_form(form);

                                form.data('styled', true);
                            },
                            onClick: function(e) {
                                alert(1);
                            }
                        },
                        {
                            //search form
                            recreateForm: true,
                            afterShowSearch: function(e) {
                                var form = $(e[0]);
                                form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
                                style_search_form(form);
                            },
                            afterRedraw: function() {
                                style_search_filters($(this));
                            },
                            /**
                            multipleGroup:true,
                            showQuery: true
                            */
                        },
                        {
                            //view record form
                            recreateForm: true,
                            beforeShowForm: function(e) {
                                var form = $(e[0]);
                                form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
                            }
                        }
                    )

                    function style_edit_form(form) {
                        //enable datepicker on "sdate" field and switches for "stock" field
                        form.find('input[name=sdate]').datepicker({
                                format: 'yyyy-mm-dd',
                                autoclose: true
                            })
                            .end().find('input[name=stock]')
                            .addClass('ace ace-switch ace-switch-5').wrap('<label class="inline" />').after('<span class="lbl"></span>');

                        //update buttons classes
                        var buttons = form.next().find('.EditButton .fm-button');
                        buttons.addClass('btn btn-sm').find('[class*="-icon"]').remove(); //ui-icon, s-icon
                        buttons.eq(0).addClass('btn-primary').prepend('<i class="icon-ok"></i>');
                        buttons.eq(1).prepend('<i class="icon-remove"></i>')

                        buttons = form.next().find('.navButton a');
                        buttons.find('.ui-icon').remove();
                        buttons.eq(0).append('<i class="icon-chevron-left"></i>');
                        buttons.eq(1).append('<i class="icon-chevron-right"></i>');
                    }

                    function style_delete_form(form) {
                        console.log("style_delete_form");
                        var buttons = form.next().find('.EditButton .fm-button');
                        console.log(buttons);
                        buttons.addClass('btn btn-sm').find('[class*="-icon"]').remove(); //ui-icon, s-icon
                        buttons.eq(0).addClass('btn-danger').prepend('<i class="icon-trash"></i>');
                        buttons.eq(1).prepend('<i class="icon-remove"></i>')
                    }

                    function style_search_filters(form) {
                        form.find('.delete-rule').val('X');
                        form.find('.add-rule').addClass('btn btn-xs btn-primary');
                        form.find('.add-group').addClass('btn btn-xs btn-success');
                        form.find('.delete-group').addClass('btn btn-xs btn-danger');
                    }

                    function style_search_form(form) {
                        var dialog = form.closest('.ui-jqdialog');
                        var buttons = dialog.find('.EditTable')
                        buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'icon-retweet');
                        buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'icon-comment-alt');
                        buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-purple').find('.ui-icon').attr('class', 'icon-search');
                    }

                    function beforeDeleteCallback(e) {
                        var form = $(e[0]);
                        if(form.data('styled')) return false;

                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_delete_form(form);

                        form.data('styled', true);
                    }

                    function beforeEditCallback(e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);
                    }

                    //it causes some flicker when reloading or navigating grid
                    //it may be possible to have some custom formatter to do this as the grid is being created to prevent this
                    //or go back to default browser checkbox styles for the grid
                    function styleCheckbox(table) {
                        /**
                        $(table).find('input:checkbox').addClass('ace')
                        .wrap('<label />')
                        .after('<span class="lbl align-top" />')
                
                
                        $('.ui-jqgrid-labels th[id*="_cb"]:first-child')
                        .find('input.cbox[type=checkbox]').addClass('ace')
                        .wrap('<label />').after('<span class="lbl align-top" />');
                    */
                    }

                    //unlike navButtons icons, action icons in rows seem to be hard-coded
                    //you can change them like this in here if you want
                    function updateActionIcons(table) {
                        /**
                        var replacement = 
                        {
                            'ui-icon-pencil' : 'icon-pencil blue',
                            'ui-icon-trash' : 'icon-trash red',
                            'ui-icon-disk' : 'icon-ok green',
                            'ui-icon-cancel' : 'icon-remove red'
                        };
                        $(table).find('.ui-pg-div span.ui-icon').each(function(){
                            var icon = $(this);
                            var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
                            if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
                        })
                        */
                    }

                    //replace icons with FontAwesome icons like above
                    function updatePagerIcons(table) {
                        var replacement = {
                            'ui-icon-seek-first': 'icon-double-angle-left bigger-140',
                            'ui-icon-seek-prev': 'icon-angle-left bigger-140',
                            'ui-icon-seek-next': 'icon-angle-right bigger-140',
                            'ui-icon-seek-end': 'icon-double-angle-right bigger-140'
                        };
                        $('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function() {
                            var icon = $(this);
                            var $class = $.trim(icon.attr('class').replace('ui-icon', ''));

                            if($class in replacement) icon.attr('class', 'ui-icon ' + replacement[$class]);
                        })
                    }

                    function enableTooltips(table) {
                        $('.navtable .ui-pg-button').tooltip({
                            container: 'body'
                        });
                        $(table).find('.ui-pg-div').tooltip({
                            container: 'body'
                        });
                    }
                    
                    $("[class='btn btn-sm btn-primary out']").click(function() {
                        download(60);
                    });
                    
                    
                });
                
            });
        </script>
    </body>

</html>