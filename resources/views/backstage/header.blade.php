<div class="navbar navbar-fixed-top navbar-default" id="navbar">
    <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
    </script>
    <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
            <a href="#" class="navbar-brand">
                <small>
                    <!--i class="icon-leaf"></i-->
                    存销宝客户管理后台
                </small>
            </a><!-- /.brand -->
        </div><!-- /.navbar-header -->

        <div class="navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <!--img class="nav-user-photo" src="assets/avatars/user.jpg" alt="Jason's Photo" /-->
                        <span class="user-info">
                        {{Session::get("mobile")}}
                        </span>
                        <i class="icon-caret-down"></i>
                    </a>

                    <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <!--li>
                            <a href="#"><i class="icon-cog"></i>Settings
                            </a>
                        </li>
                        <li>
                            <a href="#"><i class="icon-user"></i>Profile</a>
                        </li>
                        <li class="divider"></li-->
                        <li>
                            <a href="{{url('/special/center')}}"><i class="icon-user"></i>个人中心</a>
                        </li>
                        <li>
                            <a href="{{url('/special/logout')}}"><i class="icon-off"></i>退出</a>
                        </li>
                    </ul>
                </li>
            </ul><!-- /.ace-nav -->
        </div><!-- /.navbar-header -->
    </div><!-- /.container -->
</div>