@layout('layout.base')
@section('script')
    {{ HTML::script('js/category.js') }}
@endsection
@section('sidebar')
    @include('../block.sidebar')
@endsection
@section('content')
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>分类列表</span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li><a href="{{ URL::to('product') }}">产品管理</a></li>
                <li class="current"><a href="{{ URL::to('product/category') }}">分类管理</a></li>
            </ul>
        </div>
        
        <div class="breadLinks">
            <ul>
                <li><a href="#" title=""><i class="icos-list"></i><span>新订单</span> <strong>(+58)</strong></a></li>
                <li><a href="#" title=""><i class="icos-check"></i><span>新任务</span> <strong>(+12)</strong></a></li>
                <li class="has">
                    <a title="">
                        <i class="icos-money3"></i>
                        <span>快捷导航</span>
                        <span><img src="/images/elements/control/hasddArrow.png" alt=""></span>
                    </a>
                    <ul>
                        <li><a href="#" title=""><span class="icos-add"></span>New invoice</a></li>
                        <li><a href="#" title=""><span class="icos-archive"></span>History</a></li>
                        <li class="noBorderB"><a href="#" title=""><span class="icos-printer"></span>Print invoices</a></li>
                    </ul>
                </li>
            </ul>
             <div class="clear"></div>
        </div>
    </div>
    <!-- Breadcrumbs line ends -->

    <!-- Main content begins -->
    <div class="wrapper">
        <div class="widget">
            <div class="whead"><h6>分类列表</h6><div class="clear"></div></div>
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="category_list_table"></table>
            </div>
        </div>
    </div>
    <!-- Main content ends-->
    <script type="text/javascript">
        $(function() {
            cTable = $('#category_list_table').dataTable({
                bSort: false,
                bProcessing: true,
                bFilter: true,
                bServerSide: true,
                bJQueryUI: false,
                sPaginationType: 'full_numbers',
                sAjaxSource: '/product/category/filter',
                sDom: '<"H"fl<"clear">>tr<"F"ip>',
                oLanguage: { sUrl: '/js/plugins/tables/lang_cn.txt'},
                aoColumnDefs: [
                    { sTitle: "类别名称", aTargets: [0] },
                    { sTitle: "排序", aTargets: [1] },
                    { sTitle: "操作", aTargets: [2], bSearchable: false, sClass: 'tableActs', sWidth: 60 },
                ],
                fnRowCallback: function(nRow,aData, iDisplayIndex, iDisplayIndexFull) {
                    var id = aData[2];
                    var operation = '<a href="/product/category/edit?category_id=' + id + '" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon=""></span></a>' + 
                                    '<a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault tipS" original-title="删除"><span class="iconb" data-icon=""></span></a>';
                    $('td:eq(2)', nRow).html(operation);
                }
            });

        });
    </script>

    <!-- delete confirm begins-->
    <div id="category_delete_confirm" style="display:none" title="提示">
        <p>你确定删除此类别？</p>
    </div>
    <!-- delete confirm ends-->
</div>
@endsection
