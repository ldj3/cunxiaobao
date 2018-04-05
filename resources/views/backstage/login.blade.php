<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>存销宝</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<!-- basic styles -->
		<link href="{{ asset('/ace/css/bootstrap.min.css') }}" rel="stylesheet" />
		<link rel="stylesheet" href="{{ asset('/ace/css/font-awesome.min.css') }}" />
		<link rel="stylesheet" href="{{ asset('/ace/css/ace.min.css') }}" />
		<link rel="stylesheet" href="{{ asset('/ace/css/ace-rtl.min.css') }}" />
		<script src="{{ asset('/js/jquery-1.11.3.min.js') }}"></script>
	</head>

<body class="login-layout">
    <div class="main-container">
        <div class="main-content">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="login-container">
                        <div class="space-6"></div>
                        <div class="position-relative">
                            <div id="login-box" class="login-box visible widget-box no-border">
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h4 class="header blue lighter bigger">客户管理后台</h4>
                                        <div class="space-6"></div>
                                        <form id="login_form">
                                            <fieldset>
                                                <label class="block clearfix">
                                                    <span class="block input-icon input-icon-right">
                                                        <input type="text" class="form-control" name="name" id="name" placeholder="手机号码/帐号" tabindex="1"/>
                                                        <i class="icon-user"></i>
                                                    </span>
                                                </label>
												<label class="block clearfix">
                                                    <span class="block input-icon input-icon-right">
                                                        <input type="password" class="form-control" name="password" id="password" placeholder="密码" tabindex="2"/>
                                                        <i class="icon-lock"></i>
                                                    </span>
                                                </label>
												<label class="block clearfix">
													<span class="block">
														<input type="text" maxlength="6" class="form-control" name="captcha" id="captcha" placeholder="填写验证码" tabindex="3"/>
													</span>
                                                </label>
												<div class="clearfix" style="border:1px solid #f1f1f1;">
													<a onclick="javascript:re_captcha('{{URL('backstage/captcha')}}');"  id="verifyimg"><img src="{{ URL('backstage/captcha/1') }}"  alt="验证码" title="刷新图片" id="display" border="0"></a>
												</div>

                                                <div class="space"></div>

                                                <div class="clearfix">
                                                    <div class="col-xs-4">
                                                        <div class="checkbox icheck">
                                                            <label>
                                                                <input type="checkbox"> 记住密码
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <button type="button" onclick="post_login()" class="width-35 pull-right btn btn-sm btn-primary" tabindex="5">
                                                        <i class="icon-key"></i>
                                                        登录
                                                    </button>
                                                </div>

                                                <div class="space-4"></div>
                                            </fieldset>
                                        </form>
                                    </div><!-- /widget-main -->
                                    <div class="toolbar clearfix">
                                        <div>
                                            <a href="#" onclick="show_box('forgot-box'); return false;" class="forgot-password-link">
                                                <i class="icon-arrow-left"></i>忘记密码
                                            </a>
                                        </div>
                                        <div>
                                            <a href="#" onclick="show_box('register-box'); return false;" class="forgot-password-link">
                                                <i class="icon-arrow-right"></i>注册新账号
                                            </a>
                                        </div>
                                    </div>
                                </div><!-- /widget-body -->
                            </div><!-- /login-box -->
                            <div id="forgot-box" class="forgot-box widget-box no-border">
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h4 class="header red lighter bigger"><i class="icon-key"></i>密码重置</h4>
                                        <div class="space-6"></div>
                                        <form>
                                            <fieldset>
                                                <label class="row">
                                                    <div class="col-xs-6">
                                                        <input type="text" id="mobile" name="mobile" class="form-control" placeholder="手机号码" />
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <button type="button" id="send_button" class="btn btn-sm btn-danger">点击发送验证码</button>
                                                    </div>
                                                </label>
                                                <label class="block clearfix">
                                                    <span class="block input-icon input-icon-right">
                                                        <input type="password" id="pwd" name="pwd" class="form-control" placeholder="密码不可少于6位" />
                                                        <i class="icon-lock"></i>
                                                    </span>
                                                </label>
                                                <label class="block clearfix">
                                                    <span class="block input-icon input-icon-right">
                                                        <input type="text" id="verify" name="verify" class="form-control" placeholder="手机验证码" />
                                                        <i class="icon-envelope"></i>
                                                    </span>
                                                </label>
                                                <div class="clearfix">
                                                    <div class="col-xs-6">
                                                        <div class="checkbox icheck">
                                                            <label>
                                                                <input type="checkbox"> 记住密码
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <button type="button" id="submit-button" class="width-35 pull-right btn btn-sm btn-danger" >
                                                        <i class="icon-key"></i>
                                                        提交重置
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div><!-- /widget-main -->
                                    <div class="toolbar">
                                        <a href="#" onclick="show_box('login-box'); return false;" class="back-to-login-link">
                                            <i class="icon-arrow-left"></i>返回登录
                                        </a>
                                    </div>
                                </div><!-- /widget-body -->
                            </div><!-- /forgot-box -->

                            <div id="register-box" class="forgot-box widget-box no-border">
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h4 class="header red lighter bigger"><i class="icon-key"></i>注册新用户</h4>
                                        <div class="space-6"></div>
                                        <form>
                                            <fieldset>
                                                <label class="block clearfix">
                                                    <span class="block input-icon input-icon-right">
                                                        <input type="text" class="form-control" name="user" id="user" placeholder="手机号码/帐号" tabindex="1"/>
                                                        <i class="icon-user"></i>
                                                    </span>
                                                </label>
                                                <label class="block clearfix">
                                                    <span class="block input-icon input-icon-right">
                                                        <input type="password" id="repwd" name="repwd" class="form-control" placeholder="密码不可少于6位" />
                                                        <i class="icon-lock"></i>
                                                    </span>
                                                </label><label class="block clearfix">
                                                    <span class="block input-icon input-icon-right">
                                                        <input type="password" id="conpwd" name="conpwd" class="form-control" placeholder="确认密码" />
                                                        <i class="icon-lock"></i>
                                                    </span>
                                                </label>
                                                <div class="clearfix">
                                                    <div class="col-xs-6">
                                                        <div class="checkbox icheck">
                                                            <label>
                                                                <input type="checkbox"> 记住密码
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <button type="button" onclick="register_button()" class="width-35 pull-right btn btn-sm btn-danger" >
                                                        <i class="icon-key"></i>
                                                        注册成功
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div><!-- /widget-main -->
                                    <div class="toolbar clearfix">
                                        <a href="#" onclick="show_box('login-box'); return false;" class="back-to-login-link">
                                            <i class="icon-arrow-left"></i>已注册账号
                                        </a>
                                    </div>
                                </div><!-- /widget-body -->
                            </div><!-- /register-box -->
                            
                        </div><!-- /position-relative -->
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </div><!-- /.main-container -->

    <!-- basic scripts -->

    <!--[if !IE]> -->
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script type="text/javascript">
        window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
    </script>
    <!-- <![endif]-->

    <!--[if IE]>
    <script type="text/javascript">
     window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
    </script>
    <![endif]-->
    <script type="text/javascript">
        if("ontouchend" in document) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
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
                var url = '/backstage/sendmsg';
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
            var url = '/backstage/chk_auth';
            var query = $("#login_form").serialize();
            $.post(url,query,function(data){
            if(data.status)
            {
                location.href = "/backstage/index";
            }
            else
            {
                alert(data.msg);
                $("#verifyimg").trigger("click");
                return false;
            }
            }, 'json');
        }
        function show_box(id)
        {
             
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
            var url = '/backstage/forgotpwd';
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
        function register_box()
        {
            var user = $("#user").val();
            var repwd = $("#repwd").val();
            var conpwd = $("#conpwd").val();
            if(!user)
            {
                alert("手机号码不能为空");
                return false;
            }

            var reg1 = /^0?1[3|4|5|8][0-9]\d{8}$/;
            if (!reg1.test(user)) {
                alert("手机号码格式有误!");
                return false;
            };
            if(!repwd)
            {
                alert("密码不能为空");
                return false;
            }
            else
            {
                if(repwd.length<6)
                {
                    alert("密码长度不能少于6位");
                    return false;
                }
            }
            var url = '/backstage/register_pwd';
            var query = $("#register_box").serialize();
            $.post(url,query,function(data){
                if(data.status)
                {
                    location.href = "/backstage/index";
                }
                else
                {
                    alert(data.msg);
                    $("#verifyimg").trigger("click");
                    return false;
                }
            }, 'json');

        }
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
            var url = target;//"{{URL('backstage/captcha')}}"; 
            var url = url+"/"+Math.random(); 
            $("#display").attr("src",url);
            //$("#div-captcha").find("i").hide();
        }

    </script>
    
</body>
</html>