$(function(){
    var order_id = $("#order_id").val();
    if(!order_id) return false;
    var loading_html = '';
    loading_html = '<div class="panel-heading" align="center"><img height="30px" width="30px" src="../images/loading.gif"></div>';
    $("div .panel").append(loading_html);
    rankJson = {order_id:order_id};
    $.getJSON('/order/search', rankJson, function(data){
        if(data.success)
        {
            $("div .panel").empty();
            var html = "";
            if(data.arr.length == 0)
            {
                html = '<div class="panel-heading">订单不存在</div>';
            }
            else
            {
                html = '<div class="panel-heading">';
                html += '日期:'+data.modify_date+'<br>';
                html += '单号:'+data.order_no+'<br>';
                html += '客户:'+data.customer_name+'<br>';
                html += '备注:'+data.remark+'<br></div>';
                for(var i=0;i < data.arr.length;i++)
                {
                    html += '<div class="panel-heading">';
                    html += '<table border="0"><tr><td width="100" rowspan="3">';
                    html += '<img src='+data.arr[i]["src_image"]+' width="80px" height="80px"></td>';
                    html += '<td>&nbsp;&nbsp;'+data.arr[i]["goods_name"]+'</td></tr>';
                    html += '<tr><td>&nbsp;单价:'+data.arr[i]["sal_price"]+' 元/'+data.arr[i]["sal_unit"]+'&nbsp;&nbsp;&nbsp;&nbsp;数量:'+data.arr[i]["sal_qty"]+'</td></tr>';
                    html += '<tr><td>&nbsp;&nbsp;金额:'+data.arr[i]["money"]+'</td></tr></table>';
                    html += '</div>';
                }
            }
            $("div .panel").append(html);
        }
        
    });
})