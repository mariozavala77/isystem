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
var slibe;
var order_data=[];
    var sales_region_Data = [{
                country: "USA",
                visits: 3025,
                color: "#FF0F00"
            }, {
                country: "China",
                visits: 1882,
                color: "#FF6600"
            }, {
                country: "Japan",
                visits: 1809,
                color: "#FF9E01"
            }, {
                country: "Germany",
                visits: 1322,
                color: "#FCD202"
            }, {
                country: "UK",
                visits: 1122,
                color: "#F8FF01"
            }, {
                country: "France",
                visits: 1114,
                color: "#B0DE09"
            }, {
                country: "India",
                visits: 984,
                color: "#04D215"
            }, {
                country: "Spain",
                visits: 711,
                color: "#0D8ECF"
            }, {
                country: "Netherlands",
                visits: 665,
                color: "#0D52D1"
            }, {
                country: "Russia",
                visits: 580,
                color: "#2A0CD0"
            }, {
                country: "South Korea",
                visits: 443,
                color: "#8A0CCF"
            }, {
                country: "Canada",
                visits: 441,
                color: "#CD0D74"
            }];
            var product_class_statistics_Data = [{
                year: '电风扇',
                income: 3000,
                color: "#FF0F00"
            }, {
                year: '手机',
                income: 3020,
                color: "#FF6600"
            }, {
                year: '对讲机',
                income: 3400,
                color: "#FF9E01"
            }, {
                year: 'IPhone 4 / 4S',
                income: 3900,
                color: "#FCD202"
            }, {
                year: '三星I9300',
                income: 4000,
                color: "#0D52D1"
            }];
            AmCharts.ready(function () {
                sales_region(sales_region_Data);
                product_class_statistics(product_class_statistics_Data);
                generateChartData();
                orders_chart();
            });
function sales_region(chartData){
                var chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "country";
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
                graph.valueField = "visits";
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
                //valueAxis.title = "销售分类统计";
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

function orders_chart() {
    slibe = new AmCharts.AmSerialChart();
    slibe.pathToImages = "/js/plugins/amcharts/images/";
    slibe.zoomOutButton = {
        backgroundColor: '#000000',
        backgroundAlpha: 0.15
    };
    slibe.dataProvider = order_data;
    slibe.categoryField = "date";

    // listen for "dataUpdated" event (fired when chart is rendered) and call zoomChart method when it happens
    slibe.addListener("dataUpdated", zoomChart);

    // AXES
    // category
    var categoryAxis = slibe.categoryAxis;
    categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
    categoryAxis.minPeriod = "DD"; // our data is daily, so we set minPeriod to DD
    categoryAxis.dashLength = 1;
    categoryAxis.gridAlpha = 0.15;
    categoryAxis.axisColor = "#DADADA";

    // value                
    var valueAxis = new AmCharts.ValueAxis();
    valueAxis.axisAlpha = 0.2;
    valueAxis.dashLength = 1;
    valueAxis.title="金额";
    slibe.addValueAxis(valueAxis);

    // GRAPH
    var graph = new AmCharts.AmGraph();
    graph.title = "当日订单累计金额";
    graph.valueField = "order";
    graph.balloonText = "当日订单累计金额[[value]]";
    graph.hideBulletsCount = 20;
    graph.lineAlpha = 1;
    graph.bullet = "round";
    slibe.addGraph(graph);
    var graph = new AmCharts.AmGraph();
    graph.title = "当日支付累计金额支付量";
    graph.valueField = "pay";
    graph.balloonText = "当日支付累计金额[[value]]";
    graph.hideBulletsCount = 20;
    graph.lineAlpha = 1;
    graph.bullet = "round";
    slibe.addGraph(graph);
    var graph = new AmCharts.AmGraph();
    graph.title = "当日退款累计金额";
    graph.valueField = "refund";
    graph.balloonText = "当日退款累计金额[[value]]";
    graph.hideBulletsCount = 20;
    graph.lineAlpha = 1;
    graph.bullet = "round";
    slibe.addGraph(graph);
    var graph = new AmCharts.AmGraph();
    graph.title = "当日出库累计金额";
    graph.valueField = "warehousing";
    graph.balloonText = "当日出库累计金额[[value]]";
    graph.hideBulletsCount = 20;
    graph.lineAlpha = 1;
    graph.bullet = "round";
    slibe.addGraph(graph);            

    // CURSOR
    chartCursor = new AmCharts.ChartCursor();
    chartCursor.cursorPosition = "mouse";
    slibe.addChartCursor(chartCursor);

    // SCROLLBAR
    var chartScrollbar = new AmCharts.ChartScrollbar();
    chartScrollbar.graph = graph;
    chartScrollbar.scrollbarHeight = 40;
    chartScrollbar.color = "#FFFFFF";
    chartScrollbar.autoGridCount = true;
    slibe.addChartScrollbar(chartScrollbar);
    slibe.write("orders");
}
 // generate some random data, quite different range
            function generateChartData() {
                var firstDate = new Date();
                firstDate.setDate(firstDate.getDate() - 500);

                for (var i = 0; i < 500; i++) {
                    var newDate = new Date(firstDate);
                    newDate.setDate(newDate.getDate() + i);

                    var order = Math.round(Math.random() * 50) + 50;
                    var pay = Math.round(Math.random() * 10) + 50;
                    var refund = Math.round(Math.random()*1);
                    var warehousing = Math.round(Math.random()*10)+20;

                    order_data.push({
                        date: newDate,
                        order: order,
                        pay: pay,
                        refund:refund,
                        warehousing:warehousing
                    });
                }
            }

            // this method is called when chart is first inited as we listen for "dataUpdated" event
            function zoomChart() {
                // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
                slibe.zoomToIndexes(0, 30);
            }
</script>   
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
                <div class="chart" id="orders"></div>
        </div>
    </div>
    
</div>
<!-- Content ends -->    
@endsection
