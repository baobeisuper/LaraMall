s
<aside>
    <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        @inject('aside', 'App\Presenters\AsidePresenter')
        <ul class="sidebar-menu">
            <li class="{{ $aside->openTag(['index']) }}">
                <a class="" href="{{ url('/admin/index') }}">
                    <i class="icon-dashboard"></i>
                    <span>首页</span>
                </a>
            </li>
            <li class="sub-menu {{ $aside->openTag(['users','subscribers']) }}">
                <a href="javascript:;" class="">
                    <i class="icon-book"></i>
                    <span>用户管理</span>
                    <span class="arrow {{ $aside->openTag(['users','subscribers']) }}"></span>
                </a>
                <ul class="sub" {{ $aside->displayBlock(['users','subscribers']) }}>
                    <li><a class="" href="{{ url('admin/subscribers') }}">前台用户</a></li>
                    <li><a class="" href="{{ url('admin/users') }}">后台用户</a></li>
                </ul>
            </li>
            <li class="sub-menu {{ $aside->openTag(['classification']) }}">
                <a href="javascript:;" class="">
                    <i class="icon-th"></i>
                    <span>分类管理</span>
                    <span class="arrow {{ $aside->openTag(['classification']) }}"></span>
                </a>
                <ul class="sub" {{ $aside->displayBlock(['classification']) }}>
                    <li><a class="icon-align-center" href="{{ route('classification.index') }}"> 分类列表</a></li>
                    <li><a class=" icon-indent-left" href="{{ route('classification.create') }}"> 添加分类</a></li>
                </ul>
            </li>

            <li class="sub-menu {{ $aside->openTag(['categoryLabel']) }}">
                <a href="javascript:;" class="">
                    <i class="icon-th"></i>
                    <span>标签管理</span>
                    <span class="arrow {{ $aside->openTag(['categoryLabel']) }}"></span>
                </a>
                <ul class="sub" {{ $aside->displayBlock(['categoryLabel']) }}>
                    <li><a class="icon-align-center" href="{{ route('categoryLabel.index') }}"> 分类标签列表</a></li>
                    <li><a class=" icon-indent-left" href=""> 添加分类</a></li>
                </ul>
            </li>

            <li class="sub-menu {{ $aside->openTag(['goods', 'cargoList']) }}">
                <a href="javascript:;" class="">
                    <i class="icon-glass"></i>
                    <span>商品管理</span>
                    <span class="arrow {{ $aside->openTag(['goods', 'cargoList']) }}"></span>
                </a>
                <ul class="sub" {{ $aside->displayBlock(['goods', 'cargoList']) }}>
                    <li><a href="/admin/goods">商品列表</a></li>
                    <li><a href="/admin/goods/create">添加商品</a></li>
                </ul>
            </li>
            <li>
                <a class="" href="login.html">
                    <i class="icon-user"></i>
                    <span>订单管理</span>
                </a>
            </li>
            <li>
                <a class="" href="login.html">
                    <i class="icon-user"></i>
                    <span>评论管理</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="javascript:;" class="">
                    <i class="icon-th"></i>
                    <span>网站配置</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="">基础配置</a></li>
                    <li><a class="" href="dynamic_table.html">网站组件</a></li>
                    <li><a class="" href="friendLink">友情链接</a></li>
                </ul>
            </li>

            <li class="sub-menu {{ $aside->openTag(['acl', 'permission']) }}">
                <a href="javascript:;" class="">
                    <i class="icon-user-md"></i>
                    <span>权限管理</span>
                    <span class="arrow {{ $aside->openTag(['acl', 'permission']) }}"></span>
                </a>
                <ul class="sub" {{ $aside->displayBlock(['acl', 'permission']) }}>
                    <li><a class="icon-male" href="{{ route('acl.index') }}"> 角色列表</a></li>
                    <li><a class="icon-plus-sign" href="{{ route('acl.create') }}"> 添加角色</a></li>
                    <li><a class="icon-plus-sign" href="{{ route('permission.create') }}"> 添加权限</a></li>
                </ul>
            </li>

            <li class="sub-menu {{ $aside->openTag(['recommend']) }}">
                <a href="javascript:;" class="">
                    <i class="icon-briefcase"></i>
                    <span>推荐位管理</span>
                    <span class="arrow {{ $aside->openTag(['recommend']) }}"></span>
                </a>
                <ul class="sub" {{ $aside->displayBlock(['recommend']) }}>
                    <li><a class="icon-barcode" href="{{ route('recommend.index') }}"> 推荐位列表</a></li>
                    <li><a class="icon-plus-sign" href="{{ route('recommend.create') }}"> 添加推荐位</a></li>
                </ul>
            </li>

            <li>
                <a class="" href="login.html">
                    <i class="icon-user"></i>
                    <span>售后管理</span>
                </a>
            </li>
            <li>
                <a class="" href="login.html">
                    <i class="icon-user"></i>
                    <span>缓存管理</span>
                </a>
            </li>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>