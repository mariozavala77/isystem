var task_id=0;

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
                    { sTitle: "操作", aTargets: [5], bSearchable: false, sClass: "tableToolbar", sWidth: "80px" },
                ],
                fnRowCallback: function(nRow,aData, iDisplayIndex, iDisplayIndexFull) {
                    var id = aData[5];
                    var operation = '<a href="/task/edit?category_id=' + id + '" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon=""></span></a>' + 
                                    '<a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault tipS" original-title="删除" onclick="handle('+id+')"><span class="iconb" data-icon=""></span></a>'+
                                    '<div class="btn-group"><a href="#" class="tablectrl_small bDefault" data-toggle="dropdown"><span class="iconb" data-icon="&#xe1f7;"></span></a><ul class="dropdown-menu pull-right"><li><a href="#"><span class="icos-add"></span>Add</a></li><li><a href="#"><span class="icos-trash"></span>Remove</a></li><li><a href="#" class=""><span class="icos-pencil"></span>Edit</a></li><li><a href="#" class=""><span class="icos-heart"></span>Do whatever you like</a></li></ul></div>';
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
                $.post('/channel/hidden',{tasks_id:tasks_id},function(response){
                    $('#task_finish_confirm').dialog("close");
                    $.jGrowl(response.message);
                    if(response.status=='success'){
                        location.reload();
                    }
                },'json');
            },
            "取消": function () {
                $(this).dialog("close");
            }
        }
    });

});
function handle(id){
    task_id = id;
    $('#task_finish_confirm').dialog('open');
    return false;
}