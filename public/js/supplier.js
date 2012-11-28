var del_id;
$(function() {
    $('#supplier_delete_confirm').dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        buttons: {
            "删除": function() {
                $(this).dialog("close");
                $.post('/supplier/delete', {
                    channel_id: del_id
                },
                function(response) {
                    $.jGrowl(response.message);
                    if (response.status == 'success') {
                        location.reload();
                    }
                },
                'json');
            },
            "取消": function() {
                $(this).dialog("close");
            }
        }
    });
    aTable = $('#supplier_list_table').dataTable({
        bSort: false,
        bProcessing: true,
        bFilter: true,
        bServerSide: true,
        bJQueryUI: false,
        sPaginationType: 'full_numbers',
        sAjaxSource: '/supplier/filter',
        sDom: '<"H"fl<"clear">>tr<"F"ip>',
        oLanguage: {
            sUrl: '/js/plugins/tables/lang_cn.txt'
        },
        aoColumnDefs: [{
            sTitle: "名称",
            aTargets: [0],
            sWidth: '150px'
        },
        {
            sTitle: "电话",
            aTargets: [1],
            sWidth: '100px'
        },
        {
            sTitle: "邮箱",
            aTargets: [2],
            sWidth: '130px'
        },
        {
            sTitle: "地址",
            aTargets: [3]
        },
        {
            sTitle: "加入时间",
            aTargets: [4],
            bSearchable: false,
            sWidth: '110px'
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
            var operation = '<a href="/supplier/edit?supplier_id=' + id + '" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon=""></span></a>' + '<a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault tipS" original-title="删除" onclick="del(' + id + ')"><span class="iconb" data-icon=""></span></a>';
            $('td:eq(5)', nRow).html(operation);
        },
        fnDrawCallback: function() {
            $('.tableActs .tipS').tipsy({
                gravity: 's',
                fade: true,
                html: true
            });
        }
    });
});
function del(id) {
    del_id = id;
    $('#supplier_delete_confirm').dialog('open');
    return false;
}