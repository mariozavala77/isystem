@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/ui/jquery.easytabs.min.js') }}
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

        <div class="mt15">
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
                    <tfoot style="display: none">
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

                $('#order_search').live('click', function() {
                    $('.order_search').each(function() {
                        var value = $(this).val();
                        var index = $(this).attr('index');
                    
                        if(value != '') {
                            oTable.fnSetFilter(index, value);
                            //oTable.fnFilter(value, index, false, false, false, false);
                        }
                    });

                    oTable.fnDraw();
                });

                $('#order_search_reset').live('click', function() {
                    oTable.fnFilterClear();
                });
            });


            function initSearch() {
                initsearch = true;

                var country_select = '<select class="order_search" index="10"><option value>--请选择--</option>';
                @foreach($countries as $country)
                    country_select += '<option value="{{$country}}">{{$country}}';
                @endforeach
                country_select +='</select>';

                var status_select = '<select class="order_search" index="16"><option value>--请选择--</option>';
                @foreach($status as $key => $value)
                    status_select += '<option value="{{$key}}">{{$value['name']}}</option>';
                @endforeach
                status_select += '</select>';

                var source_select = '<select class="order_search" index="13"><option value>--请选择--</option>';
                @foreach($channels as $channel)
                    source_select += '<option value="{{$channel->id}}">{{$channel->name}}</option>';
                @endforeach
                source_select += '</select>';

                $('#filter_order_entity_id').html('<input class="order_search" type="text" index="1">');
                $('#filter_order_name').html('<input class="order_search" type="text" index="2">');
                $('#filter_order_country').html(country_select);
                $('#filter_order_status').html(status_select);
                $('#filter_order_source').html(source_select);

                $(".order_search").uniform();
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
                    <td field="is_broken"></td>
                    <td>同步状态:</td>
                    <td field="is_synced"></td>
                    <td>是否是AFN订单:</td>
                    <td field="is_auto"></td>
                  </tr>
                  <tr>
                    <td>购买人:</td>
                    <td field="name"></td>
                    <td>购买时间:</td>
                    <td field="purchased_at"></td>
                    <td>渠道来源:</td>
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
                    <td colspan="7" field="products" class="nopadding" style="padding: 0"></td>
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

