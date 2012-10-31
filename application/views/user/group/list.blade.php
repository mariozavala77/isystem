@layout('layout.base')
@section('script')
{{ HTML::script('js/plugins/tables/jquery.dataTables.js') }}
{{ HTML::script('js/group.js') }}
@endsection
@section('sidebar')
    @include('block.sidebar')
@endsection
@section('content')
<!-- Content begins -->
<div id="content">
    <div class="contentTop">
        <span class="pageTitle"><span class="icon-user-2">用户组</span></span>
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="index.html">Dashboard</a></li>
                <li><a href="tables.html">Tables</a>
                    <ul>
                        <li><a href="tables.html" title="">Standard tables</a></li>
                        <li><a href="tables_control.html" title="">Tables with control</a></li>
                        <li><a href="tables_sortable.html" title="">Sortable and resizable</a></li>
                    </ul>
                </li>
                <li class="current"><a href="tables_dynamic.html" title="">Dynamic table</a></li>
            </ul>
        </div>
        
        <div class="breadLinks">
            <ul>
                <li><a href="#" title=""><i class="icos-list"></i><span>Orders</span> <strong>(+58)</strong></a></li>
                <li><a href="#" title=""><i class="icos-check"></i><span>Tasks</span> <strong>(+12)</strong></a></li>
                <li class="has">
                    <a title="">
                        <i class="icos-money3"></i>
                        <span>Invoices</span>
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
            <a href="{{ URL::base() }}/user/group/add" class="buttonM bDefault floatR"><span class="icon-home-5"></span><span>添加用户组</span></a>
            <div class="clear"></div>
        </div>
        <!-- group begins -->
        <div class="widget">
            <div class="whead"><h6>用户组列表</h6><div class="clear"></div></div>
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="group_list_table"></table>
            </div>
            <script type="text/javascript">
                $(function(){
                    uTable = $('#group_list_table').dataTable({
                        bSort: false,
                        bProcessing: true,
                        bFilter: true,
                        bServerSide: true,
                        bJQueryUI: false,
                        sPaginationType: 'full_numbers',
                        sAjaxSource: '{{ URL::base() }}/user/group/filter',
                        sDom: '<"H"fl>tr<"F"ip>',
                        aoColumnDefs: [
                                        { sTitle: "名称",  aTargets: [0] },
                                        { sTitle: "操作", "aTargets": [1], bSearchable: false, sClass: "textC tableToolbar", sWidth: '80px' }
                                    ],
                        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                            var id = aData[1];
                            var operation = '<ul class="btn-group toolbar">' +
                                           '    <li>' +
                                           '        <a href="{{ URL::base() }}/user/group/edit?group_id='+id+'" data-id="' + id + '"  class="tablectrl_small bDefault edit">' +
                                           '            <span class="iconb" data-icon=""></span>' +
                                           '        </a>' +
                                           '    </li>' +
                                           '    <li>' +
                                           '        <a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault delete">' +
                                           '            <span class="iconb" data-icon=""></span>' +
                                           '        </a>' +
                                           '    </li>' +
                                           '    <li>' +
                                           '        <a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault info">' +
                                           '            <span class="iconb" data-icon=""></span>' +
                                           '        </a>' +
                                           '    </li>' +
                                           '</ul>';

                            $('td:eq(1)', nRow).html(operation);
                        },
                        oLanguage: {
                            sSearch: '搜索:',
                        }
                });

            });
            </script>
            <div class="clear"></div>
        </div>
        <!-- group ends -->
    </div>
    <!-- Main content ends -->

    <!-- delete confirm dialog begins -->
    <div id="group_delete_confirm" style="display: none" title='提示'>
        <p>你确认删除此用户组？</p>
    </div>
    <!-- delete confirm dialog ends -->
</div>
<!-- Content ends -->
@endsection
