$(document).ready(function() {
	$("#send_button").click(function(){
		var mobile = $("#mobile").val();
		if(!mobile)
		{
			alert("手机号码不能为空");
			return false;
		}
		var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;
		if (!reg.test(mobile)) {
			alert("手机号码格式有误!");
			return false;
		};
		countDown(60);
		var url = '/agent/sendmsg';
		var query = "mobile="+mobile;
		$.post(url,query,function(data){
			// alert(data.msg);                    
		});
		
	});
});
function post_login()
{
    var name = $("#name").val();
    var pwd = $("#password").val();
	var captcha = $("#captcha").val();
    if(!name)
    {
        alert("用户名不能为空");
        return false;
    }
    if(!pwd)
    {
        alert("密码不能为空");
        return false;
    }
	if(!captcha)
    {
        alert("验证码不能为空");
        return false;
    }
    var url = '/agent/chk_auth';
    var query = $("#login_form").serialize();
    $.post(url,query,function(data){
    if(data.success)
    {
        location.href = data.redirect;
    }
    else
    {
        alert(data.msg);
        $("#verifyimg").trigger("click");
        return false;
    }
    }, 'json');
}
function show_box(id) {
 jQuery('.widget-box.visible').removeClass('visible');
 jQuery('#'+id).addClass('visible');
}
$("#submit_button").click(function(){
	var mobile = $("#mobile").val();
	var pwd = $("#pwd").val();
	var verify = $("#verify").val();
	if(!mobile)
	{
		alert("手机号码不能为空");
		return false;
	}
	
	var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;
	if (!reg.test(mobile)) {
		alert("手机号码格式有误!");
		return false;
	};
	if(!pwd)
	{
		alert("密码不能为空");
		return false;
	}
	else
	{
		if(pwd.length<6)
		{
			alert("密码长度不能少于6位");
			return false;
		}
	}
	if(!verify)
	{
		alert("验证码不能为空");
		return false;
	}
	var url = '/agent/forgotpwd';
	var query = "mobile="+mobile+"&pwd="+pwd+"&verify="+verify;
	$.post(url,query,function(data){
		if(data.success)
		{
			alert(data.msg); 
			location.reload();
		}else
		{
			alert(data.msg);
		}
	});
	
});
function countDown(second)
{
	obj = $("#send_button");
	if(second >= 0)// 如果秒数还是大于0，则表示倒计时还没结束
	{
		if(typeof buttonDefaultValue === 'undefined' )
		{
			buttonDefaultValue = obj.text();        // 获取默认按钮上的文字
		}
		$("#mobile").attr("readonly",true);
		obj.attr("disabled","true");                // 按钮置为不可点击状态
		obj.text(buttonDefaultValue+'('+second+')');// 按钮里的内容呈现倒计时状态
		second--;                                   // 时间减一
		setTimeout(function(){countDown(second);},1000);// 一秒后重复执行
	}
	else// 否则，按钮重置为初始状态
	{
		obj.removeAttr("disabled");// 按钮置未可点击状态
		$("#mobile").removeAttr("readonly");
		obj.text(buttonDefaultValue);// 按钮里的内容恢复初始状态
	}  
}
function re_captcha(target) 
{ 	
	//$("#div-captcha").append('<i class="icon-spinner icon-spin orange bigger-225"></i>');
	var url = target;//"{{URL('agent/captcha')}}"; 
	var url = url+"/"+Math.random(); 
	$("#display").attr("src",url);
	//$("#div-captcha").find("i").hide();
}