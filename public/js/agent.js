$(function() {
    aTable = $('#agent_list_table').dataTable({
        bSort: false,
        bProcessing: true,
        bFilter: true,
        bServerSide: true,
        bJQueryUI: false,
        sPaginationType: 'full_numbers',
        sAjaxSource: '/agent/filter',
        sDom: '<"H"fl<"clear">>tr<"F"ip>',
        oLanguage: {
            sUrl: '/js/plugins/tables/lang_cn.txt'
        },
        aoColumnDefs: [{
            sTitle: "名称",
            aTargets: [0]
        },
        {
            sTitle: "电话",
            aTargets: [1]
        },
        {
            sTitle: "邮箱",
            aTargets: [2]
        },
        {
            sTitle: "地址",
            aTargets: [3]
        },
        {
            sTitle: "加入时间",
            aTargets: [4],
            bSearchable: false
        },
        {
            sTitle: "操作",
            aTargets: [5],
            bSearchable: false,
            sClass: "tableActs",
            sWidth: '80px'
        }],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            var id = aData[5];
            var operation = '<a href="/agent/edit?agent_id=' + id + '" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon=""></span></a>' + '<a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault tipS" original-title="删除" onclick="del('+id+')"><span class="iconb" data-icon=""></span></a>';
            $('td:eq(5)', nRow).html(operation);
        },
        fnInitComplete: function() {
            $('.select_action, select[name$="list_table_length"],.checkAll').uniform();
        },
        fnDrawCallback: function() {
            $('.tableActs .tipS').tipsy({
                gravity: 's',
                fade: true,
                html: true
            });
        }
    });
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
});

function agent_delete(agent_id) {
    $.ajax({
        url: '/agent/delete',
        data: {
            agent_id: agent_id
        },
        dataType: 'json',
        success: function(data) {
            if (typeof(data) != 'Object') {
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

function del(agent_id){
    $('#agent_delete_confirm').attr('data-id', agent_id).dialog('open');
}