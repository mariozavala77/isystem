@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/amcharts/amcharts.js') }}
@endsection
@section('sidebar')
@include('block.sidebar')
@endsection
@section('content')
<!-- Content begins --> 
<script type="text/javascript">
            var product_class_statistics_Data = [{
                year: '充电器',
                income: Math.round(Math.random() * 600)+3000,
                color: "#FF0F00"
            }, {
                year: '移动电源',
                income: Math.round(Math.random() * 600)+3020,
                color: "#FF6600"
            }, {
                year: '平板电脑',
                income: Math.round(Math.random() * 600)+3400,
                color: "#FF9E01"
            }, {
                year: '手机壳',
                income: Math.round(Math.random() * 600)+4500,
                color: "#FCD202"
            }, {
                year: '平板电脑外套',
                income: Math.round(Math.random() * 600)+4000,
                color: "#0D52D1"
            }];
            AmCharts.ready(function () {
                get_sales_region();
                product_class_statistics(product_class_statistics_Data);
                get_orders('', '');
            });
function sales_region(chartData){
                var chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "shipping_country";
                chart.startDuration = 1;

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 45; // this line makes category values to be rotated
                categoryAxis.gridAlpha = 0;
                categoryAxis.fillAlpha = 1;
                categoryAxis.fillColor = "#FAFAFA";
                categoryAxis.gridPosition = "start";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.dashLength = 5;
                //valueAxis.title = "销售区域状况"
                valueAxis.axisAlpha = 0;
                chart.addValueAxis(valueAxis);

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.valueField = "price";
                graph.colorField = "color";
                graph.balloonText = "[[category]]: [[value]]";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 1;
                chart.addGraph(graph);
                // WRITE
                chart.write("sales_region");
}
function product_class_statistics(chartData){
    // PIE CHART
               var chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "year";
                // this single line makes the chart a bar chart, 
                // try to set it to false - your bars will turn to columns                
                chart.rotate = true;

                // AXES
                // Category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.gridPosition = "start";
                categoryAxis.axisColor = "#DADADA";
                categoryAxis.fillAlpha = 1;
                categoryAxis.gridAlpha = 0;
                categoryAxis.fillColor = "#FAFAFA";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.axisColor = "#DADADA";
                valueAxis.gridAlpha = 0.1;
                chart.addValueAxis(valueAxis);

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.title = "Income";
                graph.valueField = "income";
                graph.colorField = "color";
                graph.type = "column";
                graph.balloonText = "Income in [[category]]:[[value]]";
                graph.lineAlpha = 0;
                graph.fillAlphas = 1;
                chart.addGraph(graph);

                // WRITE                                 
                chart.write("product_class_statistics");
}

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
$(function() {
    $('#fromDate,#toDate').datepicker({
        changeMonth: false,
        showOtherMonths:true,
        numberOfMonths: 1,
        dateFormat: "yy-mm-dd",
        maxDate:-1,
    });
});
function search(){
    var from = $('#fromDate').val();
    var to = $('#toDate').val();
    from = from?from:to;
    to = to?to:from;
    if(!from && !to){
        $.jGrowl('请选择筛选的日期');
        return false;
    }
    get_orders(from, to);
}
function get_orders(from, to){
    $.getJSON('/statistics/day',{start:from,end:to},function(data){
        orders_chart(data);
    });
}
function get_sales_region(){
    $.getJSON('/statistics/region',function(data){
        sales_region(data);
    });
}
</script>   
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icos-stats"></span>数据统计</span>
        
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
            <!-- Donut -->
            <div class="widget grid4 chartWrapper">
                <div class="whead"><h6>产品类别统计</h6><div class="clear"></div></div>
                <div class="chart" id="product_class_statistics"></div>
            </div>
            <!-- Auto updating chart -->
            <div class="widget grid8 chartWrapper">
                <div class="whead"><h6>销售区域状况</h6><div class="clear"></div></div>
                <div id="sales_region" class="chart"></div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="widget">
                <!--产品每日动态 包括 下单量，支付量，退款量，出库量 货物的金额-->
                <div class="whead"><h6>销售动态</h6><div class="clear"></div></div>
                <div class="formRow">
                    <div class="grid3"><label>时间检索:</label></div>
                    <div class="grid9">
                        <ul class="datesRange">
                            <li><input type="text" id="fromDate" name="from" placeholder="From" title="开始时间" readonly="readonly"/></li>
                            <li class="sep">-</li>
                            <li><input type="text" id="toDate" name="to" placeholder="To" title="结束时间" readonly="readonly"/></li>
                            <li><input type="button" value="search" onclick="search()"></li>
                        </ul>
                    </div><div class="clear"></div>
                </div>
                <div class="chart" id="orders"></div>
        </div>
    </div>
    
</div>
<!-- Content ends -->    
@endsection