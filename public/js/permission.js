$(function() {

    // 权限列表
    pTable = $('#permission_list_table').dataTable({
        bSort: false,
        bProcessing: true,
        bFilter: true,
        bServerSide: true,
        bJQueryUI: false,
        sPaginationType: 'full_numbers',
        sAjaxSource: '/user/permission/filter',
        sDom: '<"H"fl<"clear">>tr<"F"ip>',
        oLanguage: { sUrl: '/js/plugins/tables/lang_cn.txt' },
        aoColumnDefs: [
                        { sTitle: "权限操作",  aTargets: [0] },
                        { sTitle: "权限标识",  aTargets: [1] },
                        { sTitle: "操作", "aTargets": [2], bSearchable: false, sClass: "tableActs", sWidth: '80px' }
                    ],
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            var id = aData['2'];
            var operation = '<a href="/user/permission/edit?permission_id=' + id + '" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon=""></span></a>' + 
                            '<a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault tipS" original-title="删除"><span class="iconb" data-icon=""></span></a>';
            $('td:eq(2)', nRow).html(operation);
        },
        fnInitComplete: function() {
            $('.select_action, select[name$="list_table_length"],.checkAll').uniform();
        }
    });

    // 删除dialog提示
    $('#permission_delete_confirm').dialog({
        autoOpen: false, 
        width: 400,
        modal: true,
        buttons: {
                "取消": function() {
                    $(this).dialog( "close" );
                },
                "确定": function() {
                    var group_id = $('#permission_delete_confirm').attr('data-id');
                    group_delete(group_id);
                    $(this).dialog( "close" );
                }
        }
    });

    // 删除用户组
    $('a.delete').live('click', function() {
        var permission_id = $(this).attr('data-id');
        $('#permission_delete_confirm').attr('data-id', permission_id).dialog('open');

        return false;
    });

});

// 删除用户组请求
function group_delete(permission_id) {
    $.ajax({
        url: '/user/permission/delete',
        type: 'POST',
        data: {permission_id: permission_id},
        dataType: 'json',
        success: function( data ) {
            if(data.status == 'success') {
                $.jGrowl('删除成功！');
                pTable.fnDraw();
            } else if (data.status == 'fail'){
                $.jGrowl('删除失败！<br>' + data.message);
            } else {
                $.jGrowl('未知错误!');
            }
        },
        error: function() {
            $.jGrowl('删除请求失败！');
        }
    });

}
