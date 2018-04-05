function post_login()
{
    var admin_name = $("#admin_name").val();
    var pwd = $("#password").val();
	var captcha = $("#captcha").val();
    if(!admin_name)
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
    var url = '/admin/chk_auth';
    var query = $("#login_form").serialize();
    $.post(url,query,function(data){
    if(data.success)
    {
        location.href = data.redirect;
    }
    else
    {
        alert(data.msg);
        return false;
    }
    }, 'json');
}

function enter_save()
{
    var enterprise_name = $("#enterprise_name").val();
    var enterprise_contact = $("#enterprise_contact").val();
    var industry = $("#industry").val();
    var mobile = $("#mobile").val();
    var notice = "";
    if(!enterprise_name)
        notice += "企业名称不能为空\n";
    if(!enterprise_contact)
        notice += "企业联系人不能为空\n";
    if(!mobile)
        notice += "联系人手机号不能为空\n";
    if(!industry)
        notice += "行业不能为空\n";
    if(notice)
    {
        alert(notice);return false;
    }
    var url = '/admin/enterprise/dosave';
    var query = $("#enterprise").serialize();
    $.post(url,query,function(data){
    if(data.success)
    {
        alert("修改成功");
    }
    else
    {
        alert(data.msg);
        return false;
    }
    }, 'json');
}
function check_image_file(obj)
{
    if(obj.value)
    {
        if(obj.files[0].size/1024 > 1024)
        {
            alert("上传文件不能大于1M");
            return false;
        }
        var file = obj.value;
        var exp = /.jpg|.gif|.png/i; 
        if(!exp.test(file)) 
        {
            alert("上传图片必须是jpg，png或gif类型！");
            return false;
        }
        return true;
    }
    return true;
}
function check_files(obj)
{
    if(obj.value)
    {
        if(obj.files[0].size/1024 > 1024)
        {
            alert("上传文件不能大于1M");
            return false;
        }
        var file = obj.value;
        var exp = /.txt|.xls|.doc|.log/i; 
        if(!exp.test(file)) 
        {
            alert("上传文件必须是.txt|.doc文件！");
            return false;
        }
        return true;
    }
    return true;
}
function re_captcha(target) 
{
	var url = target;//"{{URL('agent/captcha')}}"; 
	var url = url+"/"+Math.random(); 
	$("#display").attr("src",url);
}