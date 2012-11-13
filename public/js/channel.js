var del_id = 0;
$(function(){
    aTable = $('#channel_list_table').dataTable({
        bSort: false,
        bProcessing: true,
        bFilter: true,
        bServerSide: true,
        bJQueryUI: false,
        sPaginationType: 'full_numbers',
        sAjaxSource: '/channel/filter',
        sDom: '<"H"fl<"clear">>tr<"F"ip>',
        oLanguage: { sUrl: '/js/plugins/tables/lang_cn.txt' },
        aoColumnDefs: [
            { sTitle: "类别",  aTargets: [0], sWidth: '80px'},
            { sTitle: "名称",  aTargets: [1], sWidth: '80px'},
            { sTitle: "别名", aTargets: [2], sWidth: '80px'},
            { sTitle: "描述", aTargets: [3]},
            { sTitle: "状态", aTargets: [4], sWidth: '40px'},
            { sTitle: "操作", "aTargets": [5], bSearchable: false, sClass: "tableActs", sWidth: '80px' }
        ],
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            var id = aData[5];
            var operation = '<a href="/channel/edit?channel_id=' + id + '" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon=""></span></a>' +
                '<a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault tipS" original-title="删除" onclick="del('+id+')"><span class="iconb" data-icon=""></span></a>';
            $('td:eq(5)', nRow).html(operation);
            var status = aData[4];
            if(status){
                var text = '<span class="fileSuccess"></span>';
            }else{
                var text = '<span class="fileError"></span>';
            }
            $('td:eq(4)', nRow).html(text);
        },
    });
    $('#channel_add_modal').dialog({
        autoOpen: false,
        modal: true,
        width:'500px',
        resizable:false
    });
    $('#channel_delete_confirm').dialog({
        autoOpen: false,
        resizable:false,
        modal: true,
        buttons: {
            "删除": function () {
                $.post('/channel/delete',{channel_id:del_id},function(response){
                    $('#channel_delete_confirm').dialog("close");
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

    $('#channel_add_modal_open').click(function (){
        $('#channel_add_modal').dialog('open');
        return false;
    });
});
function del(id){
    del_id = id;
    $('#channel_delete_confirm').dialog('open');
    return false;
}