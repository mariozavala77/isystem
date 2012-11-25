@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/charts/excanvas.min.js') }}
{{ HTML::script('js/plugins/charts/jquery.flot.js') }}
{{ HTML::script('js/plugins/charts/jquery.flot.orderBars.js') }}
{{ HTML::script('js/plugins/charts/jquery.flot.pie.js') }}
{{ HTML::script('js/plugins/charts/jquery.flot.resize.js') }}
{{ HTML::script('js/plugins/charts/jquery.sparkline.min.js') }}
@endsection
@section('sidebar')
@include('block.sidebar')
@endsection
@section('content')
<!-- Content begins -->    
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>控制中心</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li class="current"><a href="{{ URL::base() }}">控制中心</a></li>
            </ul>
        </div>
        
        <div class="breadLinks">
            <ul>
                <li><a href="#" title=""><i class="icos-list"></i><span>新订单</span> <strong>(+58)</strong></a></li>
                <li><a href="#" title=""><i class="icos-check"></i><span>新任务</span> <strong>(+12)</strong></a></li>
                <li class="has">
                    <a title="">
                        <i class="icos-money3"></i>
                        <span>快捷导航</span>
                        <span><img src="/images/elements/control/hasddArrow.png" alt=""></span>
                    </a>
                    <ul>
                        <li><a href="#" title=""><span class="icos-add"></span>New invoice</a></li>
                        <li><a href="#" title=""><span class="icos-archive"></span>History</a></li>
                        <li class="noBorderB"><a href="#" title=""><span class="icos-printer"></span>Print invoices</a></li>
                    </ul>
                </li>
            </ul>
             <div class="clear"></div>
        </div>
    </div>
    <!-- Breadcrumbs line ends -->


    <div class="wrapper">
        <div class="fluid">
            <!-- Donut -->
            <div class="widget grid4 chartWrapper">
                <div class="whead"><h6>产品库存</h6><div class="clear"></div></div>
                <div class="body"></div>
            </div>
            <!-- Auto updating chart -->
            <div class="widget grid8 chartWrapper">
                <div class="whead"><h6>销售动态</h6><div class="clear"></div></div>
                <div class="body"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="widget">
                <div class="whead"><h6>销售动态</h6><div class="clear"></div></div>
                <div class="body"></div>
        </div>
    </div>
    
</div>
<!-- Content ends -->    
@endsection
