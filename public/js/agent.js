$(function() {

    // 删除dialog提示
    $('#agent_delete_confirm').dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        buttons: {
            "取消": function() {
                $(this).dialog('close');
            },
            "确认": function() {
                var agent_id = $('#agent_delete_confirm').attr('data-id');
                agent_delete(agent_id);
                $(this).dialog('close');
            }
        }
    });

    // 删除代理
    $('a.delete').live('click', function(){
        var agent_id = $(this).attr('data-id');
        $('#agent_delete_confirm').attr('data-id', agent_id).dialog('open');

        return false;
    });
});

function agent_delete(agent_id) {
    $.ajax({
        url: '/agent/delete',
        data: {agent_id: agent_id},
        dataType: 'json',
        success: function(data) {
            if(typeof(data) != 'Object') {
                $.jGrowl(data.message);
                aTable.fnDraw();
            } else {
                $.jGrowl('未知错误!'); 
            }
        },
        error: function() {
            $.jGrowl('删除请求失败！');
        }
    });
}
