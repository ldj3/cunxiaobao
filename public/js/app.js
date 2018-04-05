function turn_login()
{
    $("#name").val('13444444444');
    $("#password").val('444444');
    dologin();
}
function dologin()
{
	var name = $("#name").val();
    var pwd = $("#password").val();
	var url = '/auth/dologin';
	var query = "name="+name+"&password="+pwd;
	$.post(url, query,function(data){
		if(data.success){
			location.href = data.redirect;
		}else{
			$("[class='alert alert-danger']").children("ul").children("li").text(data.msg);
			$("[class='alert alert-danger']").show();
			return false;
		}
	}, "json");
}
function toDecimal2(x,d)
{    
    var f = parseFloat(x);
    if (isNaN(f)) {    
        return false;    
    }    
    var f = Math.round(x*100)/100;    
    var s = f.toString();    
    var rs = s.indexOf('.');    
    if (rs < 0) {    
        rs = s.length;    
        s += '.';    
    }    
    while (s.length <= rs + d) {    
        s += '0';    
    }    
    return s;    
}