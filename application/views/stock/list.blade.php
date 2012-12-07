@layout('layout.base')
@section('script')
{{ HTML::script('js/stock.js') }}
@endsection
@section('sidebar')
    @include('block.sidebar')
@endsection
@section('content')
<!-- Content begins -->    
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-layers"></span>库存管理</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li class="current"><a href="{{ URL::to('stock') }}">库存管理</a></li>
            </ul>
        </div>
        @include('block.bread')
    </div>
    <!-- Breadcrumbs line ends -->

    <!--Main content begins-->
    <div class="wrapper">
        <div class="mt15">
            <a href="{{ URL::to('stock/import') }}" class="buttonM bDefault floatR"><span class="icon-home-5"></span><span>导入库存</span></a>
            <div class="clear"></div>
        </div>
        <!--stock begins-->
        <div class="widget fluid" id="slist">
            <div class="whead"><h6>库存列表</h6><div class="clear"></div></div> 
            <div class="hiddenpars">
                <div class="cOptions">
                    <a href="javascript:void(0);" class="tOptions tipS doFullscreen" caction="slist" title="全屏"><img src="{{ URL::base() }}/images/icons/fullscreen"/></a>
                    <a href="javascript:void(0);" ckey="slist_search" class="tOptions tipS" title="搜索"><img src="{{ URL::base() }}/images/icons/search" alt=""/></a>
                    <a href="javascript:void(0);" ckey="slist_options" class="tOptions tipS" title="设置"><img src="{{ URL::base() }}/images/icons/options" alt=""/></a>
                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="stock_list_table"></table>
            </div>
        </div>
        <!--stock ends-->

        <script type="text/javascript">
            function initSearch() {
                initsearch = true;

                var storage_select = '<select class="stock_search" index="1"><option value>--请选择--</option>';
                @foreach($storages as $storage)
                    storage_select += '<option value="{{$storage->id}}">{{$storage->area}}[{{$storage->type}}]</option>';
                @endforeach
                storage_select += '</select>';

                $('#filter_stock_name').html('<input class="stock_search" type="text" index="0"/>');
                $('#filter_stock_code').html('<input class="stock_search" type="text" index="2"/>');
                $('#filter_stock_storage').html(storage_select);

                $(".stock_search").uniform();
            }
        </script>
    </div>
    <!--Main content ends-->

    <!-- stock modify dialog begins -->
    <div id="stock_modify_dialog" title="库存调整" style="display: none">
        <div class="widget fluid">
            <div class="formRow" style="border-bottom: none">
                <div class="grid2 textR"><span>可销售：</span></div>
                <div class="grid4"><input type="text" name="sellable" /></div>
                <div class="grid2 textR"><span>不销售：</span></div>
                <div class="grid4"><input type="text" name="unsellable" /></div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <!-- stock modify dialog ends-->

    <!-- stock adjust dialog begins-->
    <div id="stock_adjust_dialog" title="调仓" style="display: none">
        <div class="widget fluid">
            <div class="formRow" style="border-bottom: none" id="adjust">
                <div class="grid2 textR">目标仓库：</div>
                <div class="grid5">
                    <select name="storage">
                        <option value>--请选择--</option>
                        @foreach($storages as $storage)
                        <option value="{{$storage->id}}">{{$storage->area}}[{{$storage->type}}]</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid2 textR">数量：</div>
                <div class="grid3"><input type="text" name="quantity" /></div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <!-- stock modify dialog ends-->

    <!-- stock entry dialog begins-->
    <div id="stock_entry_dialog" title="入库" style="display: none; padding: 0">
        <table cellpadding="0" cellspacing="0" width="100%" class="tDefault mytasks" id="entry_list_table"></table>
    </div>
    <!-- stock entry dialog ends-->

</div>
<!-- Content ends -->    
@endsection
