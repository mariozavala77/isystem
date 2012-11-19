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
                <div class="body"><div class="pie" id="donut" style="padding: 0px; position: relative; "><canvas class="base" width="246" height="250"></canvas><canvas class="overlay" width="246" height="250" style="position: absolute; left: 0px; top: 0px; "></canvas><div class="legend"><div style="position: absolute; width: 83px; height: 198px; top: 10px; right: 5px; background-color: rgb(239, 239, 239); opacity: 1; "> </div><table style="position:absolute;top:10px;right:5px;;font-size: 11px; color:#545454"><tbody><tr><td class="legendColorBox"><div style="#000"><div style="width:15px;height:0;border:3px solid rgb(238,121,81);overflow:hidden"></div></div></td><td class="legendLabel"><span>Series1</span></td></tr><tr><td class="legendColorBox"><div style="#000"><div style="width:15px;height:0;border:3px solid rgb(109,182,238);overflow:hidden"></div></div></td><td class="legendLabel"><span>Series2</span></td></tr><tr><td class="legendColorBox"><div style="#000"><div style="width:15px;height:0;border:3px solid rgb(203,75,75);overflow:hidden"></div></div></td><td class="legendLabel"><span>Series3</span></td></tr><tr><td class="legendColorBox"><div style="#000"><div style="width:15px;height:0;border:3px solid rgb(153,62,183);overflow:hidden"></div></div></td><td class="legendLabel"><span>Series4</span></td></tr><tr><td class="legendColorBox"><div style="#000"><div style="width:15px;height:0;border:3px solid rgb(59,163,170);overflow:hidden"></div></div></td><td class="legendLabel"><span>Series5</span></td></tr><tr><td class="legendColorBox"><div style="#000"><div style="width:15px;height:0;border:3px solid rgb(190,96,64);overflow:hidden"></div></div></td><td class="legendLabel"><span>Series6</span></td></tr><tr><td class="legendColorBox"><div style="#000"><div style="width:15px;height:0;border:3px solid rgb(87,145,190);overflow:hidden"></div></div></td><td class="legendLabel"><span>Series7</span></td></tr><tr><td class="legendColorBox"><div style="#000"><div style="width:15px;height:0;border:3px solid rgb(162,60,60);overflow:hidden"></div></div></td><td class="legendLabel"><span>Series8</span></td></tr><tr><td class="legendColorBox"><div style="#000"><div style="width:15px;height:0;border:3px solid rgb(122,49,146);overflow:hidden"></div></div></td><td class="legendLabel"><span>Series9</span></td></tr></tbody></table></div></div></div>
            </div>
            
            <!-- Auto updating chart -->
            <div class="widget grid8 chartWrapper">
                <div class="whead"><h6>销售动态</h6><div class="clear"></div></div>
                <div class="body"><div class="updating" style="padding: 0px; position: relative; "><canvas class="base" width="545" height="250"></canvas><canvas class="overlay" width="545" height="250" style="position: absolute; left: 0px; top: 0px; "></canvas><div class="tickLabels" style="font-size:smaller"><div class="xAxis x1Axis" style="color:#545454"><div class="tickLabel" style="position:absolute;text-align:center;left:2px;top:228px;width:49px">0</div><div class="tickLabel" style="position:absolute;text-align:center;left:52px;top:228px;width:49px">10</div><div class="tickLabel" style="position:absolute;text-align:center;left:103px;top:228px;width:49px">20</div><div class="tickLabel" style="position:absolute;text-align:center;left:154px;top:228px;width:49px">30</div><div class="tickLabel" style="position:absolute;text-align:center;left:205px;top:228px;width:49px">40</div><div class="tickLabel" style="position:absolute;text-align:center;left:256px;top:228px;width:49px">50</div><div class="tickLabel" style="position:absolute;text-align:center;left:307px;top:228px;width:49px">60</div><div class="tickLabel" style="position:absolute;text-align:center;left:358px;top:228px;width:49px">70</div><div class="tickLabel" style="position:absolute;text-align:center;left:409px;top:228px;width:49px">80</div><div class="tickLabel" style="position:absolute;text-align:center;left:460px;top:228px;width:49px">90</div><div class="tickLabel" style="position:absolute;text-align:center;left:511px;top:228px;width:49px">100</div></div><div class="yAxis y1Axis" style="color:#545454"><div class="tickLabel" style="position:absolute;text-align:right;top:212px;right:524px;width:21px">0</div><div class="tickLabel" style="position:absolute;text-align:right;top:169px;right:524px;width:21px">20</div><div class="tickLabel" style="position:absolute;text-align:right;top:127px;right:524px;width:21px">40</div><div class="tickLabel" style="position:absolute;text-align:right;top:84px;right:524px;width:21px">60</div><div class="tickLabel" style="position:absolute;text-align:right;top:42px;right:524px;width:21px">80</div><div class="tickLabel" style="position:absolute;text-align:right;top:-1px;right:524px;width:21px">100</div></div></div></div></div>
            </div>
            <div class="clear"></div>
            
        </div>
    </div>
    
</div>
<!-- Content ends -->    
@endsection
