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

    <div id="order_info_dialog" style="display: none">
        <div class="widget" style="margin-top: 0px">
            <div>
                <ul class="tbar" style="border-bottom: 0px">
                    <li><a>详细信息</a></li>
                    <li><a>物流信息</a></li>
                </ul>
            </div>
            <table  cellpadding="0" cellspacing="0" border="0" class="dTable dataTable" >
                <tbody>
                  <tr>
                    <td>ID:</td>
                    <td field="entity_id"></td>
                    <td>金额:</td>
                    <td field="total_price"></td>
                    <td>配送紧急程度:</td>
                    <td colspan="3"field="shipment_level"></td>
                  </tr>
                  </tr>
                  <tr>
                    <td>订单状态:</td>
                    <td field="status"></td>
                    <td>处理状态:</td>
                    <td field="broken"></td>
                    <td>同步状态:</td>
                    <td field="is_sync">已同步</td>
                    <td>是否是AFN订单:</td>
                    <td field="auto">是</td>
                  </tr>
                  <tr>
                    <td>购买人:</td>
                    <td field="name">$23.5</td>
                    <td>购买时间:</td>
                    <td field="purchased_at">$23.5</td>
                    <td>购买渠道:</td>
                    <td field="channel"></td>
                    <td>支付方式:</td>
                    <td field="payment_method"></td>
                  </tr>
                  <tr>
                    <td>配送地址：</td>
                    <td colspan="7" field="shipping"></td>
                  </tr>
                  <tr>
                    <td>产品信息：</td>
                    <td colspan="7" field="products"></td>
                  </tr>
                  <tr>
                    <td>生成时间：</td>
                    <td field="created_at"></td>
                    <td>更新时间：</td>
                    <td field="updated_at"></td>
                    <td>修改时间：</td>
                    <td colspan="3" field="modified_at"></td>
                  </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Content ends -->    
@endsection

