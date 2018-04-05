function post_login(){
    var user_name = $("#user_name").val();
    var pwd = $("#password").val();
    $("#tip").html();
    if(!user_name){
        $("#tip").html("出错啦！ 账号名不可为空");
        return false;
    }
    var reg = /^0?(13[0-9]|15[012356789]|18[01236789]|14[57])[0-9]{8}$/;
    var flag = reg.test(user_name);
    if(!flag){
        $("#tip").html("出错啦！ 账号名格式有误");
        return false;
    }
    if(!pwd){
        $("#tip").html("出错啦！ 密码不可为空");
        return false;
    }
    var url = '/order/chkAuth';
    var obj = new Object;
    obj.userName = user_name;
    obj.password = pwd;
    $.post(url,obj,function(data){
        if(data.status){
            location.href = data.redirect;
        }else{
            $("#tip").html("<font color='yellow'>"+data.msg+"</font>");
            return false;
        }
    }, 'json');
}
