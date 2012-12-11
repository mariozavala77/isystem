@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/amcharts/amcharts.js') }}
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
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li class="current"><a href="{{ URL::base()}}/channel" title="渠道管理">渠道管理</a></li>
            </ul>
        </div>

        @include('block.bread')
        
    </div>
    <!-- Breadcrumbs line ends -->


    <div class="wrapper">
        <div class="fluid">
            <!-- Invoice widget -->
            <div class="widget grid3">
                <div class="whead">
                <h6>订单金额</h6>
                <div class="clear"></div>
                </div>
                <div class="body" style="padding:0;">
                    <ul class="wInvoice" style="border:0;margin:0;">
                        <li style="width:100%"><h4 class="green">$2,500,593</h4><span>订单总额</span></li>
                    </ul>
                </div>
            </div>
            <!-- Invoice widget -->
            <div class="widget grid3">
                <div class="whead">
                <h6>任务</h6>
                <div class="clear"></div>
                </div>
                <div class="body" style="padding:0;">
                    <ul class="wInvoice" style="border:0;margin:0;">
                        <li style="width:50%"><h4 class="green">56</h4><span>已处理</span></li>
                        <li style="width:50%"><h4 class="red">18</h4><span>未处理</span></li>
                    </ul>
                </div>
            </div>
            <!-- Invoice widget -->
            <div class="widget grid3">
                <div class="whead">
                <h6>产品信息</h6>
                <div class="clear"></div>
                </div>
                <div class="body" style="padding:0;">
                    <ul class="wInvoice" style="border:0;margin:0;">
                        <li style="width:50%"><h4 class="green">493</h4><span>产品池</span></li>
                        <li style="width:50%"><h4 class="red">3859</h4><span>在售商品</span></li>
                    </ul>
                </div>
            </div>
            <div class="widget grid3">
                <div class="whead">
                <h6>供销关系</h6>
                <div class="titleOpt">
                  <a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="iconb" data-icon="&#xe04d;"></span><span class="clear"></span></a>
                  <ul class="dropdown-menu pull-right">
                    <li><a href="{{ URL::base()}}/agent/add" class=""><span class="icon-plus"></span>添加代理商</a></li>
                        <li><a href="{{ URL::base()}}/supplier/add" class=""><span class="icon-plus"></span>添加供应商</a></li>
                  </ul>
                </div>
                <div class="clear"></div>
                </div>
                <div class="body" style="padding:0;">
                    <ul class="wInvoice" style="border:0;margin:0;">
                        <li style="width:50%"><h4 class="green">765</h4><span>供应商</span></li>
                        <li style="width:50%"><h4 class="red">15</h4><span>代理商</span></li>
                    </ul>
                </div>
            </div>                                                            
        </div>
        <!-- Invoice widget -->
        <div class="widget">
            <div class="whead">
            <h6>订单</h6>
            <div class="clear"></div>
            </div>
            <div class="body" style="padding:0;">
                <ul class="wInvoice" style="border:0;margin:0;">
                    <li style="width:16.5%"><h4 class="green">63,734</h4><span>总数</span></li>
                    <li style="width:16.5%"><h4 class="red">46</h4><span>待付款</span></li>
                    <li style="width:16.5%"><h4 class="red">187</h4><span>未发货</span></li>
                    <li style="width:16.5%"><h4 class="red">837</h4><span>部分发货</span></li>
                    <li style="width:16.5%"><h4 class="red">16</h4><span>已取消</span></li>
                    <li style="width:16.5%"><h4 class="red">7</h4><span>无法处理</span></li>
                </ul>
            </div>
        </div>
        <div class="widget">
            <!--产品每日动态 包括 下单量，支付量，退款量，出库量 货物的金额-->
            <div class="whead"><h6>销售动态（最近两周）</h6><div class="clear"></div></div>
            <div class="chart" id="orders"></div>
        </div>                
    </div>
</div>
<!-- Content ends -->
<script type="text/javascript" charset="utf-8" async defer>
$('.dropdown-toggle').dropdown();
function orders_chart(order_data) {
    var slibe = new AmCharts.AmSerialChart();
    slibe.dataProvider = order_data;
    slibe.categoryField = "date";
    slibe.balloon.color = "#000000";
    // AXES
    // category
    var categoryAxis = slibe.categoryAxis;
    categoryAxis.dashLength = 1;
    categoryAxis.gridAlpha = 0.15;
    categoryAxis.axisColor = "#DADADA";
    categoryAxis.fillAlpha = 1;
    categoryAxis.fillColor = "#FAFAFA";
    categoryAxis.gridAlpha = 0;
    categoryAxis.axisAlpha = 0;
    categoryAxis.gridPosition = "start";
    categoryAxis.position = "bottom";

    // value                
    var valueAxis = new AmCharts.ValueAxis();
    valueAxis.axisAlpha = 0.2;
    valueAxis.dashLength = 1;
    valueAxis.title="金额";
    slibe.addValueAxis(valueAxis);

    // GRAPH
    var graph = new AmCharts.AmGraph();
    graph.title = "当日订单累计金额";
    graph.valueField = "purchased_price";
    graph.balloonText = "当日订单累计金额[[value]]";
    graph.hideBulletsCount = 40;
    graph.lineAlpha = 1;
    graph.bullet = "round";
    slibe.addGraph(graph);
    var graph = new AmCharts.AmGraph();
    graph.title = "当日支付累计金额支付量";
    graph.valueField = "unshipped_price";
    graph.balloonText = "当日支付累计金额[[value]]";
    graph.hideBulletsCount = 20;
    graph.lineAlpha = 1;
    graph.bullet = "round";
    slibe.addGraph(graph);
    var graph = new AmCharts.AmGraph();
    graph.title = "当日退款累计金额";
    graph.valueField = "unfulfillable_price";
    graph.balloonText = "当日退款累计金额[[value]]";
    graph.hideBulletsCount = 20;
    graph.lineAlpha = 1;
    graph.bullet = "round";
    slibe.addGraph(graph);
    var graph = new AmCharts.AmGraph();
    graph.title = "当日出库累计金额";
    graph.valueField = "shipped_price";
    graph.balloonText = "当日出库累计金额[[value]]";
    graph.hideBulletsCount = 20;
    graph.lineAlpha = 1;
    graph.bullet = "round";
    slibe.addGraph(graph);            

    // CURSOR
    chartCursor = new AmCharts.ChartCursor();
    chartCursor.cursorPosition = "mouse";
    slibe.addChartCursor(chartCursor);

    var legend = new AmCharts.AmLegend();
                legend.markerType = "circle";
                slibe.addLegend(legend);
    slibe.write("orders");
}
function get_main(){
    $.getJSON('/statistics/main',function(data){
        orders_chart(data.chart);
    });
}
$(function() {
    get_main();
});
</script>   
@endsection