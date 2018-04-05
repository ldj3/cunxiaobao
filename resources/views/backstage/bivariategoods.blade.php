@extends('special.app')
@section('content')
    <style type="text/css">
        body {
            background-color: #FFFFFF;
        }
        .info{
            padding-top:20px;
            font-size:14px;
            line-height:32px;
        }
        .font-weight{
            font-weight: bold;
        }
        .ulstyle{
            padding-top:7px;
            line-height:20px;
        }
        .row-fluid ul li{
            border:0;
        }
        .lists{
            padding:0 20px;
        }
        .property{
            margin:20px 0;
        }
    </style>
    <script src="{{ asset('/ace/js/jquery.colorbox-min.js') }}"></script>
@if($status)
    <div class="page-content">
        <div class="col-xs-12">
            <div class="row">
                <div class="page-header">
                    <h1><small>基本信息</small></h1>
                </div>
                <div class="info">
                    <div class="col-xs-2 text-center">
                        <div class="row-fluid text-center">
                            <ul class="ace-thumbnails text-center">
                                <li style="width:100%">
                                    <a href="{{$data["image"]}}" data-rel="colorbox">
                                        <img alt="商品图片" src="{{$data['image']}}" width="80%"/>
                                    </a>
                                </li>
                            </ul>
                            <div>
                                <a href="/special/goods/list_image?goods_id={{$data['id']}}">图片管理</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-10">
                        <div class="col-xs-12">
                            <div class="col-xs-2 text-right font-weight">
                                商品名称：
                            </div>
                            <div class="col-xs-10 text-left">
                                {{$data["name"]}}
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="col-xs-2 text-right font-weight">
                                {{$data["propertyOne"]}}：
                            </div>
                            <div class="col-xs-10">
                                {{$data["property1"]}}
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="col-xs-2 text-right font-weight">
                                {{$data["propertyTwo"]}}：
                            </div>
                            <div class="col-xs-10">
                                {{$data["property2"]}}
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="col-xs-2 text-right font-weight">
                                {{$data["propertyThree"]}}：
                            </div>
                            <div class="col-xs-10">
                                {{$data["property3"]}}
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="col-xs-2 text-right font-weight">
                                备注：
                            </div>
                            <div class="col-xs-10">
                                {{$data["remark"]}}
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <hr>
                        </div>
                        <div class="col-xs-12">
                            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>商品单位</th>
                                        <th>批发价(元)</th>
                                        <th>零售价1(元)</th>
                                        <th>零售价2(元)</th>
                                        <th>成本价(元)</th>
                                        <th>最低价(元)</th>
                                        <th>条形码1</th>
                                        <th>条形码2</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach($data["unitList"] as $key => $val)
                                    <tr>
                                        <td>{{$val["unit"]}}</td>
                                        <td>{{$val["tradePrice"]}}</td>
                                        <td>{{$val["retailPrice"]}}</td>
                                        <td>{{$val["retailPrice1"]}}</td>
                                        <td>{{$val["costPrice"]}}</td>
                                        <td>{{$val["limitPrice"]}}</td>
                                        <td>{{$val["barcode"]}}</td>
                                        <td>{{$val["barcode1"]}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="page-header">
                    <h1><small>商品信息</small></h1>
                </div>
                <div class="table-responsive lists">
                    <div class="col-xs-12 property">
                        <div class="col-xs-3">
                            {{$data['propertyTitle1']}}：
                            <select name="property1" id="property1">
                                <option value=""> --请选择-- </option>
                                @foreach($data['property']['property1'] as $key => $val)
                                    <option value="{{$val}}"> {{$val}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            {{$data['propertyTitle2']}}：
                            <select name="property2" id="property2">
                                <option value=""> --请选择-- </option>
                                @foreach($data['property']['property2'] as $key => $val)
                                    <option value="{{$val}}"> {{$val}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <button class="btn btn-purple btn-sm" type="button" onclick="ajaxpage('1')">搜索
                                <i class="icon-search icon-on-right bigger-110"></i>
                            </button>
                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>序号</th>
                                <th>{{$data['propertyTitle1']}}</th>
                                <th>{{$data['propertyTitle2']}}</th>
                                <th>批发价</th>
                                <th>零售价1</th>
                                <th>零售价2</th>
                                <th>成本价</th>
                                <th>最低价</th>
                                <th>条形码</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="showData">
                            
                        </tbody>
                    </table> 
                    <div class="row">
                            <div class="col-sm-6">
                                <div id="sample-table-2_info" class="dataTables_info">共 <span id="total">0</span> 条记录 </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_paginate paging_bootstrap">
                                    <ul class="pagination">
                                        <!--显示页数-->
                                    </ul>
                                </div>
                            </div>
                        <input type='hidden' name="page" id="page" value="1">
                    </div>
                </div>
            </div>
            
            <div class="clearfix form-actions text-center">
                <button class="btn btn-info" onclick="javascript:history.go(-1);">返 回</button>
            </div>
            <div id="modal-form" class="modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="blue bigger"></h5>
                        </div>
                        <div class="modal-body overflow-visible">
                        <!--contenct start-->
                        
                        <!--contenct end-->
                        </div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div><!-- PAGE CONTENT ENDS -->
        </div>
    </div>

    <script type="text/javascript">
        jQuery(function($) {
            var colorbox_params = {
                reposition:true,
                scalePhotos:true,
                scrolling:false,
                previous:'<i class="icon-arrow-left"></i>',
                next:'<i class="icon-arrow-right"></i>',
                close:'&times;',
                current:'{current} / {total}',
                maxWidth:'100%',
                maxHeight:'100%',
                onOpen:function(){
                    document.body.style.overflow = 'hidden';
                },
                onClosed:function(){
                    document.body.style.overflow = 'auto';
                },
                onComplete:function(){
                    $.colorbox.resize();
                }
            };

            $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
            $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");
        });
        $(document).ready(function() {
           ajaxpage($("#page").val());//直接加载数据
        });
        function ajaxpage(page){
            $('#loading').modal('show');
            $("#total").empty();
            $("#showData").empty();
            $(".pagination").empty();
            var url = '/special/goods/bivariateList';
            var query = "goodsId="+"{{$data['id']}}"+"&page="+page+"&property1="+$("#property1").val()+"&property2="+$("#property2").val();
            $.post(url,query,function(data){
                if(data.status){
                    $("#total").append(data.total);
                    if(data.total == 0){
                        $("#showData").append("<tr><td colspan='8'>没有记录</tr></td>");
                    }else{
                        var arr = data.data;
                        var total = data.total;
                        var page = data.page;
                        $("#page").val(page);
                        var html = "";
                        for(var i = 0;i < arr.length;i++){
                            html += "<tr>";
                                html += "<td>";
                                    html += i+1;
                                html += "</td>";
                                html += "<td>";
                                    html += arr[i]['propertyValue1'];
                                html += "</td>";
                                html += "<td>";
                                    html += arr[i]['propertyValue2'];
                                html += "</td>";
                                //判断二维商品是否有单位价格信息，假如没有引用主商品信息
                                if(arr[i]["unit"].length > 0){
                                    html += '<td><ul style="margin-left:12px;">';
                                        for(var a = 0;a < arr[i]["unit"].length;a++){
                                            html += '<li type="square" >'+arr[i]["unit"][a]["tradePrice"]+'( '+arr[i]["unit"][a]["unit"]+' )</li>';
                                        }
                                    html += '</ul></td>';
                                    html += '<td><ul style="margin-left:12px;">';
                                        for(var a = 0;a < arr[i]["unit"].length;a++){
                                            html += '<li type="square" >'+arr[i]["unit"][a]["retailPrice"]+'( '+arr[i]["unit"][a]["unit"]+' )</li>';
                                        }
                                    html += '</ul></td>';
                                    html += '<td><ul style="margin-left:12px;">';
                                        for(var a = 0;a < arr[i]["unit"].length;a++){
                                            html += '<li type="square" >'+arr[i]["unit"][a]["retailPrice1"]+'( '+arr[i]["unit"][a]["unit"]+' )</li>';
                                        }
                                    html += '</ul></td>';
                                    html += '<td><ul style="margin-left:12px;">';
                                        for(var a = 0;a < arr[i]["unit"].length;a++){
                                            html += '<li type="square" >'+arr[i]["unit"][a]["costPrice"]+'( '+arr[i]["unit"][a]["unit"]+' )</li>';
                                        }
                                    html += '</ul></td>';
                                    html += '<td><ul style="margin-left:12px;">';
                                        for(var a = 0;a < arr[i]["unit"].length;a++){
                                            html += '<li type="square" >'+arr[i]["unit"][a]["limitPrice"]+'( '+arr[i]["unit"][a]["unit"]+' )</li>';
                                        }
                                    html += '</ul></td>';
                                    html += '<td><ul style="margin-left:12px;">';
                                        var unit = arr[i]["unit"];
                                        for(var j = 0;j < unit.length;j++){
                                            if(unit[j]["barcode"] != null && unit[j]["barcode"] != ""){
                                                html += '<li type="square" >'+unit[j]["barcode"]+'</li>';
                                            }
                                            if(unit[j]["barcode1"] != null && unit[j]["barcode1"] != ""){
                                                html += '<li type="square" >'+unit[j]["barcode1"]+'</li>';
                                            }
                                        }
                                    html += '</ul></td>';
                                }else{
                                    
                                    html += '<td>';
                                        html += '0';
                                    html += '</td>';
                                    html += '<td>';
                                        html += '0';
                                    html += '</td>';
                                    html += '<td>';
                                        html += '0';
                                    html += '</td>';
                                    html += '<td>';
                                        html += '0';
                                    html += '</td>';
                                    html += '<td>';
                                        html += '0';
                                    html += '</td>';
                                    html += '<td>';
                                        html += '0';
                                    html += '</td>';
                                }
                                    
                                html += "<td>";
                                html += '<a href="/special/goods/bivariateInfo/'+arr[i]["id"]+'?menu_id=2-1">查看</a>';
                                html += "</td>";
                            html += "</tr>";
                        }
                        $("#showData").append(html);
                        dopage(total,page);
                        
                    }
                }else{
                    $("#total").append(0);
                    $("#showData").append("<tr><td colspan='10'>没有记录</tr></td>");
                }
                $('#loading').modal('hide');
            }, 'json');
        }
        
        function del(obj)
        {
            if(confirm("确定要删除该商品么?"))
            {
                var url = '/special/goods/bivariate/del';
                var query = "id="+obj;
                $.post(url,query,function(data){
                    alert(data.msg);
                    if(data.success){
                        //window.location.reload();
                        $(".close").click(); 
                        ajaxpage($("#page").val());
                    }
                    else
                    {
                        return false;
                    }
                });
            }
        }
        
        function edit_storage(id)//href="#modal-form"
        {
            $("[class='modal-body overflow-visible']").empty();
            $(".modal-footer").empty();
            $("[class='blue bigger']").text("商品入库");
            var url = '/special/goods/goodsFind';
            var query = "id="+id+"&idType=2";
            $.post(url,query,function(data){
                if(data.success)
                {
                    var html = '';
                    if(data.arr.length)
                    {
                        alert("记录不存在");return false;
                    }
                    //alert(data.arr.length);
                    html += '<div class="row">';
                    html += '<table align="center" width="100%">';
                    html += '<tr><td align="right"><b>名称：</b></td><td>'+data.arr['name']+'</td></tr>';
                    if(!data.arr['qty']) data.arr['qty'] = parseInt(data.arr['qty']+0);
                    for(var a = 0;a < data.warehouse.length;a++){
                        html += '<tr><td align="right"><b>'+data.warehouse[a]['warehouse_name']+'：</b></td><td>'+data.warehouse[a]['qty']+' /'+data.arr['unit']+'</td></tr>';
                    }
                    html += '<tr><td align="right"><b>仓库：</b></td><td>';
                        html += '<select name="warehouseId" id="warehouseId">';
                            html += '<option value=""> --请选择-- </option>';
                            for(var i = 0;i < data.warehouse.length;i++){
                                html += '<option value="'+data.warehouse[i]["warehouse_id"]+'">'+data.warehouse[i]['warehouse_name']+'</option>';
                            }
                        html += '</select>';
                    html += '</td></tr>';
                    html += '<tr><td align="right"><b>入库：</b></td><td><input type="text" id="in_qty" name="in_qty"/></td></tr>';
                    html += '</table></div>';
                    $("[class='modal-body overflow-visible']").append(html);
                    var footer = '<div class="modal-footer"><button class="btn btn-sm" data-dismiss="modal"><i class="icon-remove"></i>取消</button><button class="btn btn-sm btn-primary" type="button"><i class="icon-ok"></i>提交</button></div>';
                    $("[class='modal-footer']").append(footer);
                    $('#modal-form').modal();
                    $("[class='btn btn-sm btn-primary']").click(function(){
                        var in_qty = $("#in_qty").val();
                        if(!in_qty || isNaN(in_qty) || in_qty <= 0)
                        {
                            alert("库存数量输入有误");return false;
                        }
                        var url = '/special/storage/bivariateInStorage';
                        var query = "in_qty="+in_qty+"&goods_id="+id+"&warehouseId="+$("#warehouseId").val();
                        $.post(url,query,function(data){
                            if(data.success)
                            {
                                alert(data.msg);
                                $(".close").click(); 
                                ajaxpage($("#page").val());
                            }
                            else
                            {
                                alert(data.msg);
                                return false;
                            }
                        },"json")
                    });
                }
                else
                {
                    alert("data.msg");return false;
                }
            },"json")
        }
        
        function showCode(id,val)
        {
            $("[class='modal-body overflow-visible']").empty();
            $(".modal-footer").empty();
            $("[class='blue bigger']").text("条码修改");
            var html = '';
            html += '<div class="row">';
            html += '<form id="imageform" name="imageform" method="post" enctype="multipart/form-data" action="/special/goods/add_image">';
                html += '<div class="col-xs-12" style="line-height:40px;">';
                    html += '<div class="col-xs-3">';
                    html += '</div>';
                    html += '<div class="col-xs-9">';
                        html += '条码： <input type="text" id="'+id+'" value="'+val+'">';
                    html += '</div>';
                html += '</div>';
            html += '</form>';
            html += '</div>';
            $("[class='modal-body overflow-visible']").append(html);
            $('#modal-form input[type=file]').ace_file_input({//显示添加部分
                style:'well',
                btn_choose:'Drop files here or click to choose',
                btn_change:null,
                no_icon:'icon-cloud-upload',
                droppable:true,
                thumbnail:'large'
            });
            //var save_html = '<button class="btn btn-sm btn-primary image" type="button"><i class="icon-ok"></i>提交</button>';
            var id = "'"+id+"'";
            var save_html = '<button class="btn btn-sm" data-dismiss="modal"><i class="icon-remove"></i>取消</button><input onclick="edit_code('+id+')" class="btn btn-sm btn-primary image" type="button" value="提交">';
            $(".modal-footer").append(save_html);
            $("#modal-form").modal();
            
            $("[class='btn btn-sm']").click(function(){
                $("#modal-form").hide();
            });
        }
        
        //条形码删除
        function delCode(obj)
        {
            if(confirm("确定删除该条码记录么?"))
            {
                var url = '/special/goodsbarcode/save';
                var query = "id="+obj+"&op=del";
                $.post(url,query,function(data){
                    
                    if(data.success)
                    {
                        alert(data.msg);
                        window.location.reload();
                    }
                    else
                    {
                        return false;
                    }
                });
            }
        }
        
        //修改条码
        function edit_code(obj)
        {
            var barcode = $("#"+obj).val();
            var url = '/special/goodsbarcode/save';
            var query = "id="+obj+"&barcode="+barcode+"&op=edit";
            $.post(url,query,function(data){
                if(data.success)
                {
                    alert(data.msg);
                    window.location.reload();
                }
                else
                {
                    return false;
                }
            });
        }
        
        //增加条码
        function addCode()
        {
            $("[class='modal-body overflow-visible']").empty();
            $(".modal-footer").empty();
            $("[class='blue bigger']").text("新增条码");
            var html = '';
            html += '<div class="row">';
            html += '<form id="imageform" name="imageform" method="post" enctype="multipart/form-data" action="/special/goods/add_image">';
                html += '<div class="col-xs-12" style="line-height:40px;">';
                    html += '<div class="col-xs-3">';
                    html += '</div>';
                    html += '<div class="col-xs-9">';
                        html += '新增条码： <input type="text" id="newCode" value="">';
                    html += '</div>';
                html += '</div>';
            html += '</form>';
            html += '</div>';
            $("[class='modal-body overflow-visible']").append(html);
            $('#modal-form input[type=file]').ace_file_input({//显示添加部分
                style:'well',
                btn_choose:'Drop files here or click to choose',
                btn_change:null,
                no_icon:'icon-cloud-upload',
                droppable:true,
                thumbnail:'large'
            });
            var goodsId = "'"+"{{$data['id']}}"+"'";
            var save_html = '<button class="btn btn-sm" data-dismiss="modal"><i class="icon-remove"></i>取消</button><input onclick="codeAdd('+goodsId+')" class="btn btn-sm btn-primary image" type="button" value="提交">';
            $(".modal-footer").append(save_html);
            $("#modal-form").modal();
            
            $("[class='btn btn-sm']").click(function(){
                $("#modal-form").hide();
            });
        }
        
        function codeAdd(obj){
            var barcode = $("#newCode").val();
            var url = '/special/goodsbarcode/add';
            var query = "goods_id="+obj+"&barcode="+barcode;
            $.post(url,query,function(data){
                alert(data.msg);
                if(data.success)
                {
                    window.location.reload();
                }
                else
                {
                    return false;
                }
            });
        }
    </script>
@else
    <div class="page-content show" id="showInfo">
        <div class="col-xs-12">
            {{$msg}}
        </div>
    </div>
@endif
@endsection('content')