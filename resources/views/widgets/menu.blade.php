<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">菜单导航</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-edit"></i>
                    <span class="menu-item-top">内容管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    @can('@category')
                        <li><a href="/categories"><i class="fa fa-columns"></i> 栏目管理</a></li>
                    @endcan
                    @can('@content')
                        <li><a href="/contents"><i class="fa fa-file-o"></i> 内容管理</a></li>
                    @endcan
                    @can('@comment')
                        <li><a href="/comments"><i class="fa fa-comment"></i> 评论管理</a></li>
                    @endcan
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span class="menu-item-top">会员管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    @can('@member')
                        <li><a href="/members"><i class="fa fa-user"></i> 会员管理</a></li>
                    @endcan
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-calendar"></i>
                    <span class="menu-item-top">日志查询</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    @can('@push')
                        <li><a href="/push/log"><i class="fa fa-envelope"></i> 推送日志</a></li>
                    @endcan
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span class="menu-item-top">系统管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    @can('@option')
                        <li><a href="/options"><i class="fa fa-cog"></i> 系统设置</a></li>
                    @endcan
                    @can('dictionaries')
                        <li><a href="/dictionaries"><i class="fa fa-book"></i> 字典设置</a></li>
                    @endcan
                    @can('@site')
                        <li><a href="/sites"><i class="fa fa-gears"></i> 站点设置</a></li>
                    @endcan
                    @can('@app')
                        <li><a href="/apps"><i class="fa fa-android"></i> 应用管理</a></li>
                    @endcan
                    @can('@model')
                        <li><a href="/models"><i class="fa fa-book"></i> 模型管理</a></li>
                    @endcan
                    @can('@role')
                        <li><a href="/roles"><i class="fa fa-users"></i> 角色管理</a></li>
                    @endcan
                    @can('@user')
                        <li><a href="/users"><i class="fa fa-user"></i> 用户管理</a></li>
                    @endcan
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<script>
    $(document).ready(function () {
        var url = window.location.pathname;
        $('ul.treeview-menu>li').find('a[href="' + url + '"]').closest('li').addClass('active');  //二级链接高亮
        $('ul.treeview-menu>li').find('a[href="' + url + '"]').closest('li.treeview').addClass('active');  //一级栏目[含二级链接]高亮
        $('.sidebar-menu>li').find('a[href="' + url + '"]').closest('li').addClass('active');  //一级栏目[不含二级链接]高亮
    });
</script>
