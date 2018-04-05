<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <link href="{{ asset('/ace/css/bootstrap.min.css') }}" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('/ace/css/font-awesome.min.css') }}" />

        <!--[if IE 7]>
        <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
    <![endif]-->

        <!-- page specific plugin styles -->
        <!-- ace styles -->
        <link rel="stylesheet" href="{{ asset('/ace/css/jquery-ui-1.10.3.full.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('/ace/css/ui.jqgrid.css') }}" />
        <link rel="stylesheet" href="{{ asset('/ace/css/ace.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('/ace/css/ace-rtl.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('/ace/css/ace-skins.min.css') }}" />

        <!--[if lte IE 8]>
        <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
    <![endif]-->

        <!-- inline styles related to this page -->

        <!-- ace settings handler -->
    </head>

    <body>
        <script src="{{ asset('/ace/js/ace-extra.min.js') }}"></script>
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
        <script src="{{ asset('/ace/js/jquery-2.0.3.min.js') }}"></script>
        <script src="{{ asset('/ace/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('/ace/js/typeahead-bs2.min.js') }}"></script>

        <!-- page specific plugin scripts -->

        <!--[if lte IE 8]>
            <script src="/ace/js/excanvas.min.js"></script>
        <![endif]-->

        <script src="{{ asset('/ace/js/jquery-ui-1.10.3.custom.min.js') }}"></script>
        <script src="{{ asset('/ace/js/jquery.ui.touch-punch.min.js') }}"></script>
        <script src="{{ asset('/ace/js/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('/ace/js/jquery.easy-pie-chart.min.js') }}"></script>
        <script src="{{ asset('/ace/js/jquery.sparkline.min.js') }}"></script>
        <script src="{{ asset('/ace/js/flot/jquery.flot.min.js') }}"></script>
        <script src="{{ asset('/ace/js/flot/jquery.flot.pie.min.js') }}"></script>
        <script src="{{ asset('/ace/js/flot/jquery.flot.resize.min.js') }}"></script>

        <!-- ace scripts -->

        <script src="{{ asset('/ace/js/ace-elements.min.js') }}"></script>
        <script src="{{ asset('/ace/js/ace.min.js') }}"></script>

        <script src="{{ asset('/ace/js/jqGrid/jquery.jqGrid.min.js') }}"></script>
        <script src="{{ asset('/ace/js/jqGrid/i18n/grid.locale-en.js') }}"></script>
        <script type="text/javascript">
            window.jQuery || document.write("<script src='/ace/js/jquery-2.0.3.min.js'>" + "<" + "script>");
            function dopage(total, page) {
                //分页开始
                var page_html = '';
                var total_pages = parseInt(total / 20);
                if(total % 20)
                    total_pages = total_pages + 1;
                var n_disabled = ""; //下一页的按钮
                var b_disabled = ""; //上一页的按钮
                var active = "";
                if(total <= 1 || page == 1)
                    b_disabled = "disabled";
                if(total <= 1 || page == total_pages)
                    n_disabled = "disabled";
                page_html += '<li class="prev ' + b_disabled + '"><a><i class="icon-double-angle-left"></i></a></li>';
                if(page == 1)
                    active = "active";
                page_html += '<li onclick="ajaxpage(1)" class=' + active + '><a>1</a></li>';
                if(total_pages <= 10) //显示到10页的记录数
                {
                    for(var i = 2; i <= total_pages; i++) {
                        if(i == page)
                            active = "active";
                        else
                            active = "";
                        page_html += '<li onclick="ajaxpage(' + i + ')" class=' + active + '><a>' + i + '</a></li>';
                    }
                } else {
                    if(page <= 4) {
                        for(var j = 2; j <= 5; j++) {
                            if(j == page)
                                active = "active";
                            else
                                active = "";
                            page_html += '<li onclick="ajaxpage(' + j + ')" class=' + active + '><a>' + j + '</a></li>';
                        }
                        page_html += '<li><a>...</a></li>';
                        page_html += '<li onclick="ajaxpage(' + total_pages + ')" class=' + active + '><a>' + total_pages + '</a></li>';
                    } else if(page > 4 && page < parseInt(parseInt(total_pages) - 2)) {
                        page_html += '<li><a>...</a></li>';
                        for(var j = parseInt(page - 2); j < parseInt(parseInt(page) + 2); j++) {
                            if(j == page)
                                active = "active";
                            else
                                active = "";
                            page_html += '<li onclick="ajaxpage(' + j + ')" class=' + active + '><a>' + j + '</a></li>';
                        }
                        page_html += '<li><a>...</a></li>';
                        if(total_pages == page)
                            active = "active";
                        else
                            active = "";
                        page_html += '<li onclick="ajaxpage(' + total_pages + ')" class=' + active + '><a >' + total_pages + '</a></li>';
                    } else {
                        page_html += '<li><a>...</a></li>';
                        for(var j = parseInt(total_pages) - 2; j <= parseInt(total_pages); j++) {
                            if(j == page)
                                active = "active";
                            else
                                active = "";
                            page_html += '<li onclick="ajaxpage(' + j + ')" class=' + active + '><a>' + j + '</a></li>';
                        }
                    }
                }
                var onclick = " onclick=ajaxpage(" + parseInt(parseInt(page) + 1) + ")";
                if(total_pages == page) {
                    active = "active";
                    onclick = ''
                } else {
                    active = "";
                }
                page_html += '<li class="next ' + n_disabled + '" ><a ' + onclick + '><i class="icon-double-angle-right"></i></a></li>';
                $(".pagination").append(page_html);
            }
        </script>
        @yield('content')
        <!-- loading -->
        <div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop='static'>
          <div class="modal-dialog" role="document">
            <div class="modal-content loadbg">
                
                <div class="modal-body">
                    <div class="loadEffect">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <div class="loadfont">
                    <center><h3>加载中，请稍后···</h3></center>
                </div>
            </div>
          </div>
        </div>
        <style type="text/css">
            .loadbg{
                background:rgba(0,0,0,0.0);
                filter:alpha(opacity=0);
                border:0;
            }
            .loadfont{
                color:#ffffff;
                border:0;
            }
            /*LOADING 层*/
            .loadEffect{
                width: 100px;
                height: 100px;
                position: relative;
                margin: 0 auto;
                margin-top: 100px;
                margin-bottom: 100px;
            }
            .loadEffect span {
                display: inline-block;
                width: 16px;
                height: 16px;
                border-radius: 50%;
                background: #ffffff;
                position: absolute;
                -webkit-animation: load 1.04s ease infinite;
            }
            @-webkit-keyframes load {
                0% {
                    opacity: 1;
                }
                100% {
                    opacity: 0.2;
                }
            }
            .loadEffect span:nth-child(1) {
                left: 0;
                top: 50%;
                margin-top: -8px;
                -webkit-animation-delay: 0.13s;
            }
            .loadEffect span:nth-child(2) {
                left: 14px;
                top: 14px;
                -webkit-animation-delay: 0.26s;
            }
            .loadEffect span:nth-child(3) {
                left: 50%;
                top: 0;
                margin-left: -8px;
                -webkit-animation-delay: 0.39s;
            }
            .loadEffect span:nth-child(4) {
                top: 14px;
                right: 14px;
                -webkit-animation-delay: 0.52s;
            }
            .loadEffect span:nth-child(5) {
                right: 0;
                top: 50%;
                margin-top: -8px;
                -webkit-animation-delay: 0.65s;
            }
            .loadEffect span:nth-child(6) {
                right: 14px;
                bottom: 14px;
                -webkit-animation-delay: 0.78s;
            }
            .loadEffect span:nth-child(7) {
                bottom: 0;
                left: 50%;
                margin-left: -8px;
                -webkit-animation-delay: 0.91s;
            }
            .loadEffect span:nth-child(8) {
                bottom: 14px;
                left: 14px;
                -webkit-animation-delay: 1.04s;
            }
        </style>
    </body>
</html>