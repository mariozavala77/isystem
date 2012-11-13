@layout('layout.base')
@section('script')
    {{ HTML::script('js/plugins/tables/jquery.dataTable.js') }}
    {{ HTML::script('js/stock.js') }}
@endsection
@section('sidebar')
    @include('block.sidebar')
@endsection
@section('content')
<!-- Content begins -->    
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>库存管理</span>
        
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
        <div style="margin-top: 35px">
            <a href="{{ URL::to('stock/import') }}" class="buttonM bDefault floatR"><span class="icon-home-5"></span><span>导入库存</span></a>
        </div>
        <!--stock begins-->
        <div class="widget">
            <div class="whead"><h6>库存列表</h6><div class="clear"></div></div> 
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="stock_list_table"></table>
            </div>
        </div>
        <!--stock ends-->
    </div>
    <!--Main content ends-->

<script type="text/javascript">
$(function(){
    sTable = $('#stock_list_table').dataTable({
        bSort: false,
        bProcessing: true,
        bFilter: true,
        bServerSide: true,
        bJQueryUI: false,
        sPaginationType: 'full_numbers',
        sAjaxSource: '{{ URL::base() }}/stock/filter',
        sDom: '<"H"fl<"clear">>tr<"F"ip>',
        oLanguage: { sUrl: '/js/plugins/tables/lang_cn.txt' },
        aoColumnDefs: [
                { sTitle: "名称", aTargets: [0] },
                { sTitle: "编码", aTargets: [1] },
                { sTitle: "库存", aTargets: [2] },
                { sTitle: "数目", aTargets: [3] },
                { sTitle: "加入时间",aTargets: [4], bSearchable: false },
                { sTitle: "操作", aTargets: [5], bSearchable: false, sClass: "tableActs", sWidth: '80px' }
            ],
    
    });
});

</script>
    
</div>
<!-- Content ends -->    
@endsection
