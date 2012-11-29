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
var order_data=[
    {date:'11-01',order:4321,pay:3786,refund:1,warehousing:5434},
    {date:'11-02',order:3423,pay:3128,refund:1,warehousing:4324},
    {date:'11-03',order:4324,pay:4126,refund:1,warehousing:5354},
    {date:'11-04',order:3455,pay:3126,refund:1,warehousing:4232},
    {date:'11-05',order:5432,pay:5234,refund:1,warehousing:3493},
    {date:'11-06',order:4324,pay:4127,refund:1,warehousing:6453},
    {date:'11-07',order:6545,pay:6454,refund:1,warehousing:5456},
    {date:'11-08',order:3213,pay:3121,refund:1,warehousing:6753},
    {date:'11-09',order:4353,pay:4324,refund:1,warehousing:4353},
    {date:'11-10',order:5643,pay:5324,refund:1,warehousing:5467},
    {date:'11-11',order:4323,pay:3787,refund:1,warehousing:5765},
    {date:'11-12',order:4532,pay:4234,refund:1,warehousing:4543},
    {date:'11-13',order:4398,pay:3789,refund:1,warehousing:4645},
    {date:'11-14',order:4324,pay:3545,refund:1,warehousing:4547},
    {date:'11-15',order:3233,pay:2335,refund:1,warehousing:3543},
    {date:'11-16',order:3243,pay:2344,refund:0,warehousing:4522},
    {date:'11-17',order:4565,pay:3454,refund:1,warehousing:2434},
    {date:'11-18',order:4323,pay:3234,refund:1,warehousing:4631},
    {date:'11-19',order:6543,pay:5472,refund:1,warehousing:6432},
    {date:'11-20',order:3893,pay:3214,refund:1,warehousing:3453},
    {date:'11-21',order:4675,pay:4332,refund:1,warehousing:4532},
    {date:'11-22',order:6784,pay:4554,refund:1,warehousing:5433},
    {date:'11-23',order:7432,pay:5734,refund:1,warehousing:6314},
    {date:'11-24',order:8904,pay:8452,refund:1,warehousing:7454},
    {date:'11-25',order:9435,pay:8563,refund:1,warehousing:6543},
    {date:'11-26',order:8954,pay:8324,refund:1,warehousing:9234},
    {date:'11-27',order:9932,pay:9534,refund:1,warehousing:11325},
    {date:'11-28',order:11245,pay:10879,refund:1,warehousing:12657},
    {date:'11-29',order:4321,pay:3678,refund:1,warehousing:4532},
];
    var sales_region_Data = [{
                country: "US",
                visits: Math.round(Math.random() * 600)+2132,
                color: "#FF0F00"
            }, {
                country: "GB",
                visits: Math.round(Math.random() * 600)+1822,
                color: "#F8FF01"
            },{
                country: "CA",
                visits: Math.round(Math.random() * 600)+674,
                color: "#CD0D74"
            },{
                country: "NO",
                visits: Math.round(Math.random() * 600)+89,
                color: "#0D52D1"
            }];
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
                sales_region(sales_region_Data);
                product_class_statistics(product_class_statistics_Data);
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
    graph.valueField = "order";
    graph.balloonText = "当日订单累计金额[[value]]";
    graph.hideBulletsCount = 40;
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

    var legend = new AmCharts.AmLegend();
                legend.markerType = "circle";
                slibe.addLegend(legend);
    slibe.write("orders");
}
$(function() {
    $('#fromDate, #toDate').datepicker({
        changeMonth: false,
        showOtherMonths:true,
        numberOfMonths: 1,
    });
});
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
                <!--<div class="formRow">
                        <div class="grid3"><label>Dates range:</label></div>
                        <div class="grid9">
                            <ul class="datesRange">
                                <li><input type="text" id="fromDate" name="from" placeholder="From" title="开始时间"/></li>
                                <li class="sep">-</li>
                                <li><input type="text" id="toDate" name="to" placeholder="To" title="结束时间"/></li>
                            </ul>
                        </div><div class="clear"></div>
                    </div>-->
                <div class="chart" id="orders"></div>
        </div>
    </div>
    
</div>
<!-- Content ends -->    
@endsection