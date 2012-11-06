@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/tables/jquery.dataTables.js') }}
{{ HTML::script('js/agent.js') }}
@endsection
@section('sidebar')
    @include('block.sidebar')
@endsection
@section('content')
<!-- Content begins -->    
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2"></span>代理管理</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li class="current"><a href="{{ URL::base()}}/agent" title="">代理管理</a></li>
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
                        <span><img src="images/elements/control/hasddArrow.png" alt=""></span>
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

    <!-- Main content bigins -->
    <div class="wrapper">

        <div class="mt30 ">
            <a href="{{ URL::base() }}/agent/add" class="buttonM bDefault floatR"><span class="icon-home-5"></span><span>添加代理</span></a>
            <div class="clear"></div>
        </div>

        <!--agent begins-->
        <div class="widget">
            <div class="whead"><h6>代理列表</h6><div class="clear"></div></div>
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="agent_list_table"></table>
            </div>
            <script type="text/javascript">
                $(function(){
                    aTable = $('#agent_list_table').dataTable({
                        bSort: false,
                        bProcessing: true,
                        bFilter: true,
                        bServerSide: true,
                        bJQueryUI: false,
                        sPaginationType: 'full_numbers',
                        sAjaxSource: '{{ URL::base() }}/agent/filter',
                        sDom: '<"H"fl>tr<"F"ip>',
                        aoColumnDefs: [
                                        { sTitle: "名称",  aTargets: [0] },
                                        { sTitle: "电话", aTargets: [1] },
                                        { sTitle: "邮箱", aTargets: [2] },
                                        { sTitle: "地址",aTargets: [3] },
                                        { sTitle: "加入时间",aTargets: [4], bSearchable: false },
                                        { sTitle: "操作", "aTargets": [5], bSearchable: false, sClass: "textC tableToolbar", sWidth: '80px' }
                                    ],
                        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                            var id = aData[5];
                            var operation = '<ul class="btn-group toolbar">' +
                                           '    <li>' +
                                           '        <a href="{{ URL::to('/agent/edit') }}?agent_id=' + id + '" class="tablectrl_small bDefault edit">' +
                                           '            <span class="iconb" data-icon=""></span>' +
                                           '        </a>' +
                                           '    </li>' +
                                           '    <li>' +
                                           '        <a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault delete">' +
                                           '            <span class="iconb" data-icon=""></span>' +
                                           '        </a>' +
                                           '    </li>' +
                                           '</ul>';

                            $('td:eq(5)', nRow).html(operation);
                        },
                        oLanguage: {
                            sSearch: '搜索:',
                        }
                });

            });
            </script>
            <div class="clear"></div>
        </div>
        <!-- agent ends -->

        <!--delete confirm begins -->
        <div id='agent_delete_confirm' style='display: none' title='提示'>
            <p>你确认删除此代理？</p>
        </div>
    </div>
    <!-- Main content ends -->
</div>
<!-- Content ends -->    
@endsection
