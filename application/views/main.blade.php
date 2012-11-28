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
                        <li style="width:100%"><h4 class="green">$63,456</h4><span>订单总额</span></li>
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
                        <li style="width:50%"><h4 class="green">63,456</h4><span>已处理</span></li>
                        <li style="width:50%"><h4 class="red">16,542</h4><span>未处理</span></li>
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
                        <li style="width:50%"><h4 class="green">63,456</h4><span>产品池</span></li>
                        <li style="width:50%"><h4 class="red">16,542</h4><span>在售商品</span></li>
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
                        <li style="width:50%"><h4 class="green">63,456</h4><span>供应商</span></li>
                        <li style="width:50%"><h4 class="red">16,542</h4><span>代理商</span></li>
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
                    <li style="width:16.5%"><h4 class="green">63,456</h4><span>总数</span></li>
                    <li style="width:16.5%"><h4 class="red">16,542</h4><span>待付款</span></li>
                    <li style="width:16.5%"><h4 class="red">16,542</h4><span>未发货</span></li>
                    <li style="width:16.5%"><h4 class="red">16,542</h4><span>部分发货</span></li>
                    <li style="width:16.5%"><h4 class="red">16,542</h4><span>已取消</span></li>
                    <li style="width:16.5%"><h4 class="red">16,542</h4><span>无法处理</span></li>
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
var slibe;
var order_data=[];
$('.dropdown-toggle').dropdown();
function date (format, timestamp) {
  var that = this,
    jsdate, f, formatChr = /\\?([a-z])/gi,
    formatChrCb,
    _pad = function (n, c) {
      if ((n = n + '').length < c) {
        return new Array((++c) - n.length).join('0') + n;
      }
      return n;
    },
    txt_words = ["Sun", "Mon", "Tues", "Wednes", "Thurs", "Fri", "Satur", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  formatChrCb = function (t, s) {
    return f[t] ? f[t]() : s;
  };
  f = {
    // Day
    d: function () { // Day of month w/leading 0; 01..31
      return _pad(f.j(), 2);
    },
    D: function () { // Shorthand day name; Mon...Sun
      return f.l().slice(0, 3);
    },
    j: function () { // Day of month; 1..31
      return jsdate.getDate();
    },
    l: function () { // Full day name; Monday...Sunday
      return txt_words[f.w()] + 'day';
    },
    N: function () { // ISO-8601 day of week; 1[Mon]..7[Sun]
      return f.w() || 7;
    },
    S: function () { // Ordinal suffix for day of month; st, nd, rd, th
      var j = f.j();
      return j < 4 | j > 20 && ['st', 'nd', 'rd'][j%10 - 1] || 'th';
    },
    w: function () { // Day of week; 0[Sun]..6[Sat]
      return jsdate.getDay();
    },
    z: function () { // Day of year; 0..365
      var a = new Date(f.Y(), f.n() - 1, f.j()),
        b = new Date(f.Y(), 0, 1);
      return Math.round((a - b) / 864e5) + 1;
    },

    // Week
    W: function () { // ISO-8601 week number
      var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3),
        b = new Date(a.getFullYear(), 0, 4);
      return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
    },

    // Month
    F: function () { // Full month name; January...December
      return txt_words[6 + f.n()];
    },
    m: function () { // Month w/leading 0; 01...12
      return _pad(f.n(), 2);
    },
    M: function () { // Shorthand month name; Jan...Dec
      return f.F().slice(0, 3);
    },
    n: function () { // Month; 1...12
      return jsdate.getMonth() + 1;
    },
    t: function () { // Days in month; 28...31
      return (new Date(f.Y(), f.n(), 0)).getDate();
    },

    // Year
    L: function () { // Is leap year?; 0 or 1
      var j = f.Y();
      return j%4==0 & j%100!=0 | j%400==0;
    },
    o: function () { // ISO-8601 year
      var n = f.n(),
        W = f.W(),
        Y = f.Y();
      return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
    },
    Y: function () { // Full year; e.g. 1980...2010
      return jsdate.getFullYear();
    },
    y: function () { // Last two digits of year; 00...99
      return (f.Y() + "").slice(-2);
    },

    // Time
    a: function () { // am or pm
      return jsdate.getHours() > 11 ? "pm" : "am";
    },
    A: function () { // AM or PM
      return f.a().toUpperCase();
    },
    B: function () { // Swatch Internet time; 000..999
      var H = jsdate.getUTCHours() * 36e2,
        // Hours
        i = jsdate.getUTCMinutes() * 60,
        // Minutes
        s = jsdate.getUTCSeconds(); // Seconds
      return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
    },
    g: function () { // 12-Hours; 1..12
      return f.G() % 12 || 12;
    },
    G: function () { // 24-Hours; 0..23
      return jsdate.getHours();
    },
    h: function () { // 12-Hours w/leading 0; 01..12
      return _pad(f.g(), 2);
    },
    H: function () { // 24-Hours w/leading 0; 00..23
      return _pad(f.G(), 2);
    },
    i: function () { // Minutes w/leading 0; 00..59
      return _pad(jsdate.getMinutes(), 2);
    },
    s: function () { // Seconds w/leading 0; 00..59
      return _pad(jsdate.getSeconds(), 2);
    },
    u: function () { // Microseconds; 000000-999000
      return _pad(jsdate.getMilliseconds() * 1000, 6);
    },

    // Timezone
    e: function () {
      throw 'Not supported (see source code of date() for timezone on how to add support)';
    },
    I: function () { 
      var a = new Date(f.Y(), 0),
        // Jan 1
        c = Date.UTC(f.Y(), 0),
        // Jan 1 UTC
        b = new Date(f.Y(), 6),
        // Jul 1
        d = Date.UTC(f.Y(), 6); // Jul 1 UTC
      return 0 + ((a - c) !== (b - d));
    },
    O: function () { // Difference to GMT in hour format; e.g. +0200
      var tzo = jsdate.getTimezoneOffset(),
        a = Math.abs(tzo);
      return (tzo > 0 ? "-" : "+") + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
    },
    P: function () { // Difference to GMT w/colon; e.g. +02:00
      var O = f.O();
      return (O.substr(0, 3) + ":" + O.substr(3, 2));
    },
    T: function () {
      return 'UTC';
    },
    Z: function () { // Timezone offset in seconds (-43200...50400)
      return -jsdate.getTimezoneOffset() * 60;
    },

    // Full Date/Time
    c: function () { // ISO-8601 date.
      return 'Y-m-d\\TH:i:sP'.replace(formatChr, formatChrCb);
    },
    r: function () { // RFC 2822
      return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
    },
    U: function () { // Seconds since UNIX epoch
      return jsdate / 1000 | 0;
    }
  };
  this.date = function (format, timestamp) {
    that = this;
    jsdate = (timestamp == null ? new Date() : // Not provided
      (timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
      new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
    );
    return format.replace(formatChr, formatChrCb);
  };
  return this.date(format, timestamp);
}

function generateChartData() {
    var firstDate = new Date();
    firstDate.setDate(firstDate.getDate()-14);
    for (var i = 0; i < 15; i++) {
        var newDate = new Date(firstDate);
        newDate.setDate(newDate.getDate() + i);
        var order = Math.round(Math.random() * 1000) + 150;
        var pay = Math.round(Math.random() * 600) + 50;
        var refund = Math.round(Math.random()*1);
        var warehousing = Math.round(Math.random()*90)+20;
        order_data.push({
            date: date('Y-m-d', newDate),
            order: order,
            pay: pay,
            refund:refund,
            warehousing:warehousing
        });
    }
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
AmCharts.ready(function () {
    generateChartData();
    orders_chart();
});    
</script>   
@endsection