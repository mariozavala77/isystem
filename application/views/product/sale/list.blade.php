@layout('layout.base')
@section('script')
{{ HTML::script('js/sale.js') }}
@endsection
@section('sidebar')
@include('../block.sidebar')
@endsection
@section('content')
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-box"></span>销售列表</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::to('product') }}">产品管理</a></li>
                <li class="current"><a href="{{ URL::to('product/sale') }}">销售列表</a></li>
            </ul>
        </div>
        
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content begins -->
    <div class="wrapper">
        <div class="widget fluid" id="slist">
            <div class="whead"><h6>销售列表</h6><div class="clear"></div></div>
            <div class="hiddenpars">
                <div class="cOptions">
                    <a href="javascript:void(0);" class="tOptions tipS doFullscreen" caction="slist" title="全屏"><img src="{{ URL::base() }}/images/icons/fullscreen"/></a>
                    <a href="javascript:void(0);" ckey="slist_search" class="tOptions tipS" title="搜索"><img src="{{ URL::base() }}/images/icons/search" alt=""/></a>
                    <a href="javascript:void(0);" ckey="slist_options" class="tOptions tipS" title="设置"><img src="{{ URL::base() }}/images/icons/options" alt=""/></a>
                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="sale_list_table"></table>
            </div>
        </div>
        <script type="text/javascript">
            $(function()
            {
                $('a[ckey="slist_search"]').click(function(){
                    if(!initsearch) initSearch();
                });

                // 执行搜索
                $('#sale_search').live('click', function() {
                    $('.sale_search').each(function() {
                        var value = $(this).val();
                        var index = $(this).attr('index');

                        if(value != '') {
                            sTable.fnSetFilter(index, value);
                        } else {
                            sTable.fnSetFilter(index, '');
                        }
                    
                    });

                    sTable.fnDraw();
                });

                // 重置搜索
                $('#sale_search_reset').live('click', function() {
                    sTable.fnFilterClear();
                });
            });

            function initSearch()
            {
                initsearch = true;

                var channel_select = '<select class="sale_search" index="3"><option value="">--请选择--</option>';
                @foreach($channels as $channel)
                    channel_select += '<option value="{{$channel->name}}">{{$channel->name}}</option>';
                @endforeach
                channel_select += '</select>';
                
                var agent_select = '<select class="sale_search" index="4"><option value="">--请选择--</option>';
                @foreach($agents as $agent)
                    agent_select += '<option value="{{$agent->company}}">{{$agent->company}}</option>';
                @endforeach
                agent_select += '</select>';

                var status_select = '<select class="sale_search" index="5"><option value="">--请选择--</option>';
                @foreach($status as $key => $value)
                    status_select += '<option value="{{$key}}">{{$value}}</option>';
                @endforeach
                status_select += '</select>';

                $('#filter_sale_title').html('<input class="sale_search" type="text" index="0"/>');
                $('#filter_sale_sku').html('<input class="sale_search" type="text" index="1"/>');
                $('#filter_sale_channel').html(channel_select);
                $('#filter_sale_agent').html(agent_select);
                $('#filter_sale_status').html(status_select);

                $(".sale_search").uniform();
            }
        </script>
    </div>

    <!-- product sell dialog begins-->
    <div id="product_sell_dialog" title="渠道上架" style="display: none;">
        <ul class="sChannel"></ul>
    </div>
    <!-- product sell dialog ends-->

    <!-- delete confirm begins-->
    <div id="sale_delete_confirm" style="display:none" title="提示">
        <p>你确定下架此产品？</p>
    </div>
    <!-- delete confirm ends-->
</div>
@endsection
