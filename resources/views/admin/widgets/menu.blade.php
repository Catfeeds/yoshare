<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">菜单导航</li>
            {!! \App\Helpers\HtmlBuilder::menuTree(auth()->user()->site->menus()->where('parent_id', 0)->orderBy('sort')->get()) !!}
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-calendar"></i>
                    <span class="menu-item-top">日志查询</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    @can('@push')
                        <li><a href="/admin/push/log"><i class="fa fa-envelope"></i> 推送日志</a></li>
                    @endcan
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-calendar"></i>
                    <span class="menu-item-top">主题设置</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/admin/templates"><i class="fa fa-envelope"></i> 模板设置</a></li>
                    <li><a href="/admin/styles"><i class="fa fa-envelope"></i> 样式设置</a></li>
                    <li><a href="/admin/scripts"><i class="fa fa-envelope"></i> 脚本设置</a></li>
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
                        <li><a href="/admin/options"><i class="fa fa-cog"></i> 系统设置</a></li>
                    @endcan
                    @can('dictionaries')
                        <li><a href="/admin/dictionaries"><i class="fa fa-book"></i> 字典设置</a></li>
                    @endcan
                    @can('@site')
                        <li><a href="/admin/sites"><i class="fa fa-gears"></i> 站点设置</a></li>
                    @endcan
                    @can('@app')
                        <li><a href="/admin/apps"><i class="fa fa-android"></i> 应用管理</a></li>
                    @endcan
                    @can('@module')
                        <li><a href="/admin/modules"><i class="fa fa-book"></i> 模块管理</a></li>
                    @endcan
                    @can('@menu')
                        <li><a href="/admin/menus"><i class="fa fa-book"></i> 菜单管理</a></li>
                    @endcan
                    @can('@role')
                        <li><a href="/admin/roles"><i class="fa fa-users"></i> 角色管理</a></li>
                    @endcan
                    @can('@user')
                        <li><a href="/admin/users"><i class="fa fa-user"></i> 用户管理</a></li>
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
        $('ul.treeview-menu>li').find('a[href="' + url + '"]').closest('li.treeview').addClass('active');  //一级链接高亮
    });
</script>
