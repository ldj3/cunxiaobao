$(function(){
    var iNow = 1;
    $(".loading-com").text("点击加载更多");
    rank(iNow);
    $(".loading-com").click(function(){
        rank(iNow);
    });
    function rank(s)
    {
        var rows = 3;
        var page = s;
        var rankJson = {
			rows : rows,
			page : s,
		}
        $(".loading-com").empty();
        $(".loading-com").hide();
        $('.loading').show();
        $.getJSON('/order/search', rankJson, function(data){
            if(data.success)
            {
                var html = "";
                arr_order = data.arr;
                arr_order_sub = data.sub_arr;
                if(arr_order.length == 0)
                {
                    if(s == 1)
                    {
                        $("div .panel").hide();
                    }
                    $('.loading').hide();
                    $(".loading-com").text("没有更多数据了");
                    $(".loading-com").show();
                    return false;
                }
                else
                {
                    for(var i=0;i < arr_order.length;i++)
                    {   
                        var show_str = '<div class="panel-heading" style="backgroud-color:#ffffff;" onclick="show_it(\''+arr_order[i]['modify_date']+'\')"><span>'+arr_order[i]['modify_date']+'</span><br><span>金额：<font color="red">'+arr_order[i]['total_money']+'</font></span><br><span>利润：<font color="green">'+arr_order[i]['total_lr']+'</font></span><br><span>欠款：<font color="red">'+arr_order[i]['total_qk']+'</font></span><br></div>';
                        $("div .panel").append(show_str);
                        for(var j = 0;j < arr_order_sub[i].length;j++)
                        {
                            var _tmp_arr = arr_order_sub[i][j];
                            var show_str_sub = '<div class="panel-heading" onclick="getlist(\''+_tmp_arr["modify_date"]+'\')" name="'+arr_order[i]['modify_date']+'" style="display:block;">';
                            show_str_sub += '<table border="0"><tr><td><b>'+_tmp_arr["modify_date"]+'</b></td><td>&nbsp;</td><td>单数：'+_tmp_arr["total_shumu"]+'<br>利润：<span class="spc-green">'+_tmp_arr["total_lr"]+'</span></td><td>总额：<span class="spc-red">'+_tmp_arr["total_money"]+'</span><br>欠款：<span class="spc-red">'+_tmp_arr["total_qk"]+'</span></td></tr></table>';
                            show_str_sub += '</div>';
                            $("div .panel").append(show_str_sub);
                        }
                    }
                }
                iNow++;
                $('.loading').hide();
                $(".loading-com").text("点击加载更多");
                $(".loading-com").show();
            }
        });
    }
})
function getlist(opt)
{
    location.href = '/order/dolist?chkdate='+opt;
}
function show_it(obj)
{
    var objs = $('div[name="'+obj+'"]');
    /*alert(objs.length);*/
    $('div[name="'+obj+'"]').each(function(){
        if($(this).css('display') == "block")
        {
            $(this).hide();
        }
        else if($(this).css('display') == "none")
        {
            $(this).show(200);
        }
      });
}