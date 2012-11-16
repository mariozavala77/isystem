var task_id=0;
//$('.dropdown-menu').dropdown();
$(function() {
            tTable = $('#task_list_table').dataTable({
                bSort: false,
                bProcessing: true,
                bFilter: true,
                bServerSide: true,
                bJQueryUI: false,
                sPaginationType: 'full_numbers',
                sAjaxSource: '/task/filter',
                sDom: '<"H"fl<"clear">>tr<"F"ip>',
                oLanguage: { sUrl: '/js/plugins/tables/lang_cn.txt' },
                aoColumnDefs: [
                    { sTitle: "任务类型", aTargets: [0], sWidth: '60px'},
                    { sTitle: "发布人", aTargets: [1], sWidth: '50px'},
                    { sTitle: "内容", aTargets: [2] },
                    { sTitle: "级别", aTargets: [3], sWidth: '30px' },
                    { sTitle: "分配时间", aTargets: [4], sWidth: '110px' },
                    { sTitle: "操作", aTargets: [5], bSearchable: false, sClass: "tableActs", sWidth: "100px" },
                ],
                fnRowCallback: function(nRow,aData, iDisplayIndex, iDisplayIndexFull) {
                    var id = aData[5];
                    var operation = '<a href="/task/info?task_id=' + id + '" class="tablectrl_small bDefault tipS" original-title="详情"><span class="iconb" data-icon=""></span></a>' + 
                                    '<a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault tipS" original-title="标记处理状态" onclick="handle('+id+')"><span class="iconb" data-icon=""></span></a>';        
                    $('td:eq(5)', nRow).html(operation);
                    if(aData[1]){
                        var username=aData[1];
                    }else{
                        var username='系统';
                    }
                    $('td:eq(1)', nRow).html(username);
                }
            });

    $('#task_finish_confirm').dialog({
        autoOpen: false,
        resizable:false,
        modal: true,
        buttons: {
            "已处理": function () {
                var msg = $('#message').val();
                if(empty(msg)){
                    $('#message').focus();
                    $.jGrowl('请填写备注信息！');
                    return false;
                }
                $.post('/task/hidden',{tasks_id:tasks_id,handle:1,comment:msg},function(response){
                    $.jGrowl(response.message);
                    if(response.status=='success'){
                        $('#message').val('');
                        $(this).dialog("close");
                        location.reload();
                    }
                },'json');
            },
            "取消": function () {
                $(this).dialog("close");
            }
        }
    });
    $('#task_finish').dialog({
        autoOpen: false,
        resizable:false,
        modal: true,
        buttons: {
            "确认": function () {
                $(this).dialog("close");
                $('#task_finish_confirm').dialog('open');
            },
            "取消": function () {
                $(this).dialog("close");
            }
        }
    });
});
function handle(id){
    task_id = id;
    $('#task_finish').dialog('open');
    return false;
}