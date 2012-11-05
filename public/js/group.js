$(function() {

    // 用户组列表
    gTable = $('#group_list_table').dataTable({
        bSort: false,
        bProcessing: true,
        bFilter: true,
        bServerSide: true,
        bJQueryUI: false,
        sPaginationType: 'full_numbers',
        sAjaxSource: '/user/group/filter',
        sDom: '<"H"fl>tr<"F"ip>',
        aoColumnDefs: [
                        { sTitle: "名称",  aTargets: [0] },
                        { sTitle: "操作", "aTargets": [1], bSearchable: false, sClass: "textC tableToolbar", sWidth: '80px' }
                    ],
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            var id = aData[1];
            var operation = '<ul class="btn-group toolbar">' +
                           '    <li>' +
                           '        <a href="/user/group/edit?group_id='+id+'" data-id="' + id + '"  class="tablectrl_small bDefault edit">' +
                           '            <span class="iconb" data-icon=""></span>' +
                           '        </a>' +
                           '    </li>' +
                           '    <li>' +
                           '        <a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault delete">' +
                           '            <span class="iconb" data-icon=""></span>' +
                           '        </a>' +
                           '    </li>' +
                           '</ul>';

            $('td:eq(1)', nRow).html(operation);
        },
        oLanguage: {
            sSearch: '搜索:',
        }
    });
    
    // 删除dialog提示
    $('#group_delete_confirm').dialog({
        autoOpen: false, 
        width: 400,
        modal: true,
        buttons: {
                "取消": function() {
                    $(this).dialog( "close" );
                },
                "确定": function() {
                    var group_id = $('#group_delete_confirm').attr('data-id');
                    group_delete(group_id);
                    $(this).dialog( "close" );
                }
        }
    });

    // 删除用户组
    $('a.delete').live('click', function() {
        var group_id = $(this).attr('data-id');
        $('#group_delete_confirm').attr('data-id', group_id).dialog('open');

        return false;
    });

});

// 删除用户组请求
function group_delete(group_id) {
    $.ajax({
        url: '/user/group/delete',
        type: 'POST',
        data: {group_id: group_id},
        dataType: 'json',
        success: function( data ) {
            if(data.status == 'success') {
                $.jGrowl('删除成功！');
                gTable.fnDraw();
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
