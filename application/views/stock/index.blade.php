@layout('layout.base')
@section('script')
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
    </div>
    <!--Main content ends-->

<script type="text/javascript">
    initsearch = false;
    $(function(){
        sTable = $('#stock_list_table').dataTable({
            bSort: false,
            bProcessing: true,
            bFilter: true,
            bServerSide: true,
            bJQueryUI: false,
            sPaginationType: 'full_numbers',
            sAjaxSource: '{{ URL::base() }}/stock/filter',
            sDom: '<"H"<"#slist_options"l<"clear">><"#slist_search"<"clear">>>tr<"F"ip>',
            oLanguage: { sUrl: '/js/plugins/tables/lang_cn.txt' },
            aoColumnDefs: [
                    { sTitle: "名称", aTargets: [0] },
                    { sTitle: "仓库", aTargets: [1], sWidth: '80px' },
                    { sTitle: "编码", aTargets: [2], sWidth: '100px' },
                    { sTitle: "可销售", aTargets: [3], sWidth: '90px' },
                    { sTitle: "不可销售", aTargets: [4], sWidth: '90px' },
                    { sTitle: "加入时间",aTargets: [5], bSearchable: false, sWidth: '120px' },
                    { sTitle: "操作", aTargets: [6], bSearchable: false, sClass: "tableActs", sWidth: '80px' }
                ],
            fnRowCallback: function(nRow, aData, iDisplayIndexFull, iDisplayIndexFull) {
                var id = aData[6];

                // 操作
                var operation =  '<a class="tablectrl_small bDefault tipS" action="order_ship" cid="'+id+'" original-title="入库"><span class="iconb" data-icon="&#xe14a"></span></a>';
                    operation += '<a class="tablectrl_small bDefault tipS" action="order_ship" cid="'+id+'" original-title="调仓"><span class="iconb" data-icon="&#xe063"></span></a>';

                // 操作提示
                $('td:eq(6)', nRow).html(operation).find('.tipS').tipsy({gravity: 's',fade: true, html:true});
            },
            fnDrawCallback: function() {
                $('#stock_list_table').css('width', '100%');
            },
            fnInitComplete: function() {

                // 初始化搜索
                var search = '<div class="formRow" style="border-bottom: none">' +
                             '  <div class="grid1 textR">' +
                             '      <span>名称：</span>' +
                             '  </div>' +
                             '  <div id="filter_stock_name" class="grid2"></div>' +
                             '  <div class="grid1 textR">' +
                             '      <span>编码：</span>' +
                             '  </div>' +
                             '  <div id="filter_stock_code" class="grid2"></div>' +
                             '  <div class="grid1 textR">' +
                             '      <span>仓库：</span>' +
                             '  </div>' +
                             '  <div id="filter_stock_storage" class="grid2"></div>' +
                             '  <div class="grid2">' +
                             '      <span class="buttonS bBlue" id="stock_search">搜索</span>' +
                             '      <span class="buttonS bDefault" id="stock_search_reset">重置</span>' +
                             '  </div>' +
                             '  <div class="clear"></div>' +
                             '</div>';

                $('#slist_search').html(search);

                $('#stock_list_table_length').addClass('mb15');
                $('.select_action, select[name$="list_table_length"], .checkAll').uniform();
            }
        
        });

        // 搜索选项卡初始化搜索
        $('a[ckey="slist_search"]').click(function() {
            if(!initsearch) initSearch();
        });

        // 搜索
        $('#stock_search').live('click', function() {
        
            $('.stock_search').each(function() {
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
        $('#stock_search_reset').live('click', function() {
            sTable.fnFilterClear();
        });

    });

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
<!-- Content ends -->    
@endsection
