@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/ui/jquery.easytabs.min.js') }}
{{ HTML::script('js/order.js') }}
{{ HTML::script('js/plugins/tables/jquery.dataTables.columnFilter.js') }}
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

        <div class="mt30 ">
            <div class="clear"></div>
        </div>
        <!-- orders list begins -->
        <div class="widget fluid" id="olist">
            <div class="whead"><h6>订单列表</h6><div class="clear"></div></div>
            <ul class="tToolbar">
                <li><a id="sync"><span class="icos-refresh"></span>同步订单</a></li>
            </ul>
            <div class="hiddenpars">
                 <div class="cOptions">
                    <a href="javascript:void(0);" class="tOptions tipS doFullscreen" caction="olist" title="全屏"><img src="{{ URL::base() }}/images/icons/fullscreen"/></a>
                    <a href="javascript:void(0);" ckey="olist_search" class="tOptions tipS" title="搜索"><img src="{{ URL::base() }}/images/icons/search" alt=""/></a>
                    <a href="javascript:void(0);" ckey="olist_options" class="tOptions tipS" title="设置"><img src="{{ URL::base() }}/images/icons/options" alt=""/></a>
                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="order_list_table">
                    <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot><!--列搜索专用-->
                </table>
            </div>
        </div>
        <script type="text/javascript">
            $(function() {
                $('a[ckey="olist_search"]').click(function(){
                    if(!initsearch)
                        initSearch();
                });
            });


            function initSearch() {
                initsearch = true;
                oTable.columnFilter({
                    //bUseColVis: true,
                    aoColumns: [
                        null,
                        { type: "text", sSelector: "#filter_order_entity_id" },
                        { type: "text", sSelector: "#filter_order_name"},
                        null,
                        { type: "select", sSelector: "#filter_order_country", values: {{$countries}} },
                        { type: "select", sSelector: "#filter_order_source", values: ['1']},
                        null,
                        null,null,null,null
                        ]
            });

            }
        </script>
        
        <!-- orders list ends -->
    </div>
    <!-- Main content ends -->

    <!-- order info dialog begins -->
    <div id="order_info_dialog" style="display: none">
        <div class="widget" style="margin-top: 0px">
            <ul class="tbar tabs" style="border-bottom: 0px">
                <li><a href="#tab_info">详细信息</a></li>
                <li><a href="#tab_ship">物流信息</a></li>
            </ul>
            <div class="tab_container">
            <div id="tab_info" class="tab_content nopadding">
            <table cellpadding="0" cellspacing="0" border="0" class="dTable dataTable" >
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
                    <td field="is_sync"></td>
                    <td>是否是AFN订单:</td>
                    <td field="auto"></td>
                  </tr>
                  <tr>
                    <td>购买人:</td>
                    <td field="name"></td>
                    <td>购买时间:</td>
                    <td field="purchased_at"></td>
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
            <div id="tab_ship" class="tab_content nopadding">
                <table>
                </table>
            </div>
            </div>
        </div>
    </div>
    <!-- order info dialog ends -->

    <!-- order batch operation begins -->
    <div id="order_batch_ship_dialog" title="订单批量发货" style="display: none">
        <form action="{{ URL::to('order/ship') }}" method="POST">
            <div class="widget fluid" style="margin-top: 0px">
                <div class="formRow ">
                    <div class="grid3">&nbsp;</div>
                    <div class="grid1">配送公司：</div>
                    <div class="grid2"><input name="ship_company" type="text"/></div>
                    <div class="grid1">配送方式：</div>
                    <div class="grid2"><input name="ship_method" type="text"/></div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="textC mt10"><input type="submit" class="buttonS bBlue" value="发货" /></div>
        </form>
    </div>
    <!-- order batch operation ends -->

</div>
<!-- Content ends -->    
@endsection

