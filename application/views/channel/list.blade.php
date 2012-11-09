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
        <span class="pageTitle"><span class="icon-user-2"></span>渠道管理</span>
        
        <div class="clear"></div>
    </div>

    <!-- Breadcrumbs line begins -->
    <div class="breadLine">
        <div class="bc">
            <ul id="breadcrumbs" class="breadcrumbs">
                <li><a href="{{ URL::base() }}">控制中心</a></li>
                <li class="current"><a href="{{ URL::base()}}/channel" title="渠道管理">渠道管理</a></li>
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
        <div style="margin-top: 35px">
            <a href="javascript:void(0);" class="buttonM bDefault floatR" id="channel_add_modal_open"><span class="icon-home-5"></span><span>添加渠道</span></a>
            <div class="clear"></div>
        </div>

        <!--agent begins-->
        <div class="widget">
            <div class="whead"><h6>渠道列表</h6><div class="clear"></div></div>
            <div class="shownpars">
                <table cellpadding="0" cellspacing="0" border="0" class="dTable" id="channel_list_table"></table>
            </div>
            <script type="text/javascript">
                var del_id = 0;
                $(function(){
                    aTable = $('#channel_list_table').dataTable({
                        bSort: false,
                        bProcessing: true,
                        bFilter: true,
                        bServerSide: true,
                        bJQueryUI: false,
                        sPaginationType: 'full_numbers',
                        sAjaxSource: '{{ URL::base() }}/channel/filter',
                        sDom: '<"H"fl<"clear">>tr<"F"ip>',
                        oLanguage: { sUrl: '/js/plugins/tables/lang_cn.txt' },
                        aoColumnDefs: [
                                        { sTitle: "类别",  aTargets: [0] },
                                        { sTitle: "名称",  aTargets: [1] },
                                        { sTitle: "别名", aTargets: [2] },
                                        { sTitle: "加入时间", aTargets: [3] ,bSearchable: false},
                                        { sTitle: "操作", "aTargets": [4], bSearchable: false, sClass: "tableActs", sWidth: '80px' }
                                    ],
                        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                            var id = aData[4];
                            var operation = '<a href="/channel/edit?channel_id=' + id + '" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon=""></span></a>' + 
                                            '<a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault tipS" original-title="删除" onclick="del('+id+')"><span class="iconb" data-icon=""></span></a>';
                            $('td:eq(4)', nRow).html(operation);
                        },
                });
                    $('#channel_add_modal').dialog({
                        autoOpen: false,
                        width: 400,
                        modal: true,
                        buttons: {
                            "取消": function() {
                                $( this ).dialog( "close" );
                            }
                        }
                    });
                    $('#channel_delete_confirm').dialog({
                        autoOpen: false,
                        width: 400,
                        buttons: {
                            "删除": function () {
                                $.post('{{ URL::base() }}/channel/delete',{channelid:del_id},function(response){
                                    alert(response.message);
                                    $(this).dialog("close");
                                },'json');
                            },
                            "取消": function () {
                                $(this).dialog("close");
                            }
                        }
                    });

                    $('#channel_add_modal_open').click(function () {
                        $('#channel_add_modal').dialog('open');
                        return false;
                    });
            });
                function del(id){
                    del_id = id;
                    $('#channel_delete_confirm').dialog('open');
                    return false;
                }
            </script>
            <div class="clear"></div>
        </div>
        <!-- agent ends -->

        <!--delete confirm begins -->
        <div id='channel_delete_confirm' style='display: none' title='提示'>
            <p>你确认删除此渠道？</p>
        </div>
        <!--产品添加-->
        <div id="channel_add_modal" style='display: none' title='渠道添加类别选择'>
            @foreach ($channel_type as $channel)
            <a class="buttonM bDefault floatR" href="{{ URL::base() }}/channel/add?type={{ $channel['en'] }}" title="添加{{ $channel['cn'] }}渠道">{{ $channel['en'] }}</a>
            @endforeach
        </div>
    </div>
    <!-- Main content ends -->
</div>
<!-- Content ends -->    
@endsection
