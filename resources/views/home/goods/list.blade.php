@extends('home.layouts.master')

@section('title')
    商品列表页
@stop

@section('coreCss')
    <link href="/AmazeUI-2.4.2/assets/css/amazeui.css" rel="stylesheet" type="text/css"/>
    <link href="/AmazeUI-2.4.2/assets/css/admin.css" rel="stylesheet" type="text/css"/>
@stop

@section('externalCss')
    <link href="/basic/css/demo.css" rel="stylesheet" type="text/css"/>
    <link href="/css/seastyle.css" rel="stylesheet" type="text/css"/>
@stop

@section('coreJs')
    <script type="text/javascript" src="/basic/js/jquery-1.7.min.js"></script>
    <script type="text/javascript" src="/js/script.js"></script>
@stop

@section('header')
    @include('home.public.amContainer')
@stop

@section('nav')
    @include('home.public.nav')
@stop

@section('content')
    <b class="line"></b>
    <div class="search">
        <div class="search-list">
            <div class="nav-table">
                <div class="long-title"><span class="all-goods">全部分类</span></div>
                <div class="nav-cont">
                    <ul>
                        <li class="index"><a href="/home/index">首页</a></li>
                        <li class="qc"><a href="#">闪购</a></li>
                        <li class="qc"><a href="#">限时抢</a></li>
                        <li class="qc"><a href="#">团购</a></li>
                        <li class="qc last"><a href="#">大包装</a></li>
                    </ul>
                    <div class="nav-extra">
                        <i class="am-icon-user-secret am-icon-md nav-user"></i><b></b>我的福利
                        <i class="am-icon-angle-right" style="padding-left: 10px;"></i>
                    </div>
                </div>
            </div>

            <div class="am-g am-g-fixed">
                <div class="am-u-sm-12 am-u-md-12">
                    <div class="theme-popover">
                        <div class="searchAbout">
                            <span class="font-pale">相关搜索：</span>
                            <a title="坚果" href="#">坚果</a>
                            <a title="瓜子" href="#">瓜子</a>
                            <a title="鸡腿" href="#">豆干</a>

                        </div>
                        <ul class="select">
                            <p class="title font-normal">
                                <span class="fl">松子</span>
                                <span class="total fl">搜索到<strong class="num">997</strong>件相关商品</span>
                            </p>
                            <div class="clear"></div>
                            <li class="select-result">
                                <dl>
                                    <dt>已选</dt>
                                    <dd class="select-no"></dd>
                                    <p class="eliminateCriteria">清除</p>
                                </dl>
                            </li>
                            <div class="clear"></div>
                            <li class="select-list">
                                <dl id="select1">
                                    <dt class="am-badge am-round">品牌</dt>

                                    <div class="dd-conent">
                                        <dd class="select-all selected"><a href="#">全部</a></dd>
                                        <dd><a href="#">百草味</a></dd>
                                        <dd><a href="#">良品铺子</a></dd>
                                        <dd><a href="#">新农哥</a></dd>
                                        <dd><a href="#">楼兰蜜语</a></dd>
                                        <dd><a href="#">口水娃</a></dd>
                                        <dd><a href="#">考拉兄弟</a></dd>
                                    </div>

                                </dl>
                            </li>
                            <li class="select-list">
                                <dl id="select2">
                                    <dt class="am-badge am-round">种类</dt>
                                    <div class="dd-conent">
                                        <dd class="select-all selected"><a href="#">全部</a></dd>
                                        <dd><a href="#">东北松子</a></dd>
                                        <dd><a href="#">巴西松子</a></dd>
                                        <dd><a href="#">夏威夷果</a></dd>
                                        <dd><a href="#">松子</a></dd>
                                    </div>
                                </dl>
                            </li>
                            <li class="select-list">
                                <dl id="select3">
                                    <dt class="am-badge am-round">选购热点</dt>
                                    <div class="dd-conent">
                                        <dd class="select-all selected"><a href="#">全部</a></dd>
                                        <dd><a href="#">手剥松子</a></dd>
                                        <dd><a href="#">薄壳松子</a></dd>
                                        <dd><a href="#">进口零食</a></dd>
                                        <dd><a href="#">有机零食</a></dd>
                                    </div>
                                </dl>
                            </li>

                        </ul>
                        <div class="clear"></div>
                    </div>
                    <div class="search-content">
                        <div class="sort">
                            <li class="first"><a title="综合">综合排序</a></li>
                            <li><a title="销量">销量排序</a></li>
                            <li><a title="价格">价格优先</a></li>
                            <li class="big"><a title="评价" href="#">评价为主</a></li>
                        </div>
                        <div class="clear"></div>

                        <ul class="am-avg-sm-2 am-avg-md-3 am-avg-lg-4 boxes">
                            @foreach($cargos as $cargo)
                                <li>
                                    <div class="i-pic limit">
                                        <a href="/home/goodsDetail/{{ $cargo->id }}"><img src="{{ env('QINIU_DOMAIN') }}{{ $cargo->cargo_cover }}?imageView2/1/w/430/h/430"/></a>
                                        <p class="title fl">{{ $cargo->cargo_name }}</p>
                                        <p class="price fl">
                                            <b>¥</b>
                                            <strong>{{ $cargo->cargo_price }}</strong>
                                        </p>
                                        <p class="number fl">
                                            销量<span>1110</span>
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="search-side">

                        <div class="side-title">
                            经典搭配
                        </div>

                        <li>
                            <div class="i-pic check">
                                <img src="/images/cp.jpg"/>
                                <p class="check-title">萨拉米 1+1小鸡腿</p>
                                <p class="price fl">
                                    <b>¥</b>
                                    <strong>29.90</strong>
                                </p>
                                <p class="number fl">
                                    销量<span>1110</span>
                                </p>
                            </div>
                        </li>
                        <li>
                            <div class="i-pic check">
                                <img src="/images/cp2.jpg"/>
                                <p class="check-title">ZEK 原味海苔</p>
                                <p class="price fl">
                                    <b>¥</b>
                                    <strong>8.90</strong>
                                </p>
                                <p class="number fl">
                                    销量<span>1110</span>
                                </p>
                            </div>
                        </li>
                        <li>
                            <div class="i-pic check">
                                <img src="/images/cp.jpg"/>
                                <p class="check-title">萨拉米 1+1小鸡腿</p>
                                <p class="price fl">
                                    <b>¥</b>
                                    <strong>29.90</strong>
                                </p>
                                <p class="number fl">
                                    销量<span>1110</span>
                                </p>
                            </div>
                        </li>

                    </div>
                    <div class="clear"></div>

                    <!--分页 -->
                    @if(!$cargos->isEmpty())
                        <div class="am-pagination">
                            {{ $cargos->render() }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="footer">
                <div class="footer-hd">
                    <p>
                        <a href="#">恒望科技</a>
                        <b>|</b>
                        <a href="#">商城首页</a>
                        <b>|</b>
                        <a href="#">支付宝</a>
                        <b>|</b>
                        <a href="#">物流</a>
                    </p>
                </div>
                <div class="footer-bd">
                    <p>
                        <a href="#">关于恒望</a>
                        <a href="#">合作伙伴</a>
                        <a href="#">联系我们</a>
                        <a href="#">网站地图</a>
                        <em>© 2015-2025 Hengwang.com 版权所有</em>
                    </p>
                </div>
            </div>
        </div>

    </div>

    @include('home.public.navCir')

    @include('home.public.tip')

    <div class="theme-popover-mask"></div>
@stop

@section('customJs')
    <script>
        window.jQuery || document.write('<script src="basic/js/jquery-1.9.min.js"><\/script>');
    </script>
    <script type="text/javascript" src="/basic/js/quick_links.js"></script>
@stop