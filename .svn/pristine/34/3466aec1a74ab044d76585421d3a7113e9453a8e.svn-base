$(function(){
    var iNow = 1;
    $(".loading-com").text("点击加载更多");
    rank(iNow);
    $(".loading-com").click(function(){
        rank(iNow);
    });
    
    function rank(s)
    {
        var rows = 10;
        var page = s;
        var orderby = $("#orderby").val();
        var orderby_f = $("#orderby_f").val();
        var rankJson = {
			rows : rows,
			page : s,
            orderby : orderby,
            orderby_f : orderby_f,
		}
        $(".loading-com").empty();
        $(".loading-com").hide();
        $('.loading').show();
        var total = $("#total").val();
        if(total && total < (s-1)*rows)
        {
            $(".loading").hide();
            $(".loading-com").text("没有更多数据了");
            $(".loading-com").show();
            return false;
        }
        $.getJSON('customs/search', rankJson, function(data){
            if(data.success)
            {
                var html = "";
                window.SpicPage = data.total_num;
                if(data.arr.length == 0)
                {
                    $(".loading-com").text("没有更多数据了");
                    $(".loading-com").show();
                    return false;
                }
                else
                {
                    for(var i=0;i < data.arr.length;i++)
                    {   
                        html += '<div class="panel-heading">';
                        html += '<table border="0">';
                        html += '<tr><td>客户:'+data.arr[i]["name"]+'</td>';
                        html += '<td>&nbsp;&nbsp;金额:'+data.arr[i]["sum_total_money"]+'</td></tr>';
                        html += '<tr><td>毛利率:'+data.arr[i]["sum_mlr"]+'%</td>';
                        html += '<td>&nbsp;&nbsp;利润:'+data.arr[i]["sum_lr"]+'</td></tr></table>';
                        html += '</div>';
                    }
                }
                if( s == 1 ){
                    $('div .panel').empty();
                }
                $("div .panel").append(html);
                iNow++;
                $('.loading').hide();
                $("#total").val(data.total_num);
                if(data.total_num && data.total_num < s*rows)
                {
                    $(".loading-com").text("没有更多数据了");
                    $(".loading-com").show();
                }
                else
                {
                    $(".loading-com").text("点击加载更多");
                    $(".loading-com").show();
                }
            }
        });
    }
})
function change_order(obj)
{
    $("#orderby").val(obj);
    var orderby_f = $("#orderby_f").val();
    if(orderby_f == "desc")
    {
        orderby_f = 'asc';
    }
    else if(orderby_f == "asc")
    {
        orderby_f = 'desc';
    }
    $("#orderby_f").val(orderby_f);
    location.reload();
}
