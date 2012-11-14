@layout('layout.base')
@section('script')
{{ HTML::script('js/order.js') }}
@endsection
@section('sidebar')
    @include('block.sidebar')
@endsection
@section('content')
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>订单处理</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li class="current"><a href="{{ URL::to('order') }}" title="">订单处理</a></li>
            </ul>
        </div>
        
        @include('block.bread')

    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content begins -->
    <div class="wrapper">

        <!--ul class="middleNavR">
            <li><a id="rsync" href="javascript:;" title="同步订单" class="tipN"><span class="iconb step" data-icon=""></span></a>@if($total['order'])<strong>{{ $total['order'] }}</strong>@endif</li>
            <li><a id="handle" href="javascript:;" title="处理订单" class="tipN"><span class="iconb step" data-icon=""></span></a>@if($total['order'])<strong>{{ $total['order'] }}</strong>@endif</li>
            <li><a id="ship" href="javascript:;" title="订单发货" class="tipN"><span class="iconb step" data-icon=""></span></a>@if($total['order'])<strong>{{ $total['order'] }}</strong>@endif</li>
            <li><a id="track" href="javascript:;" title="订单跟踪" class="tipN"><span class="iconb step" data-icon=""></span></a>@if($total['order'])<strong>{{ $total['order'] }}</strong>@endif</li>
        </ul-->

        <div class="mt30 ">
            <div class="clear"></div>
        </div>
        <!-- products list begins -->
        <div class="widget">
            <div class="whead"><h6>订单列表</h6><div class="clear"></div></div>
            <ul class="tToolbar">
                <li><a id="sync"><span class="icos-refresh"></span>同步订单</a></li>
            </ul>
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="order_list_table"></table>
            </div>
        </div>
        <!-- products list ends -->
    </div>
    <!-- Main content ends -->
</div>
<!-- Content ends -->    
@endsection

