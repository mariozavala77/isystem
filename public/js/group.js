$(function() {
    
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
        type: 'json',
        success: function( data ) {
            if(data.status == 'success') {
                $.jGrowl('删除成功！');
                uTable.fnDraw();
            } else {
                $.jGrowl('删除失败！<br>' + data.message);
            }
        },
        error: function() {
            $.jGrowl('删除请求失败！');
        }
    });

}
