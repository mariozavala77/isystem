$(function() {
    cTable = $('#category_list_table').dataTable({
        bSort: false,
        bProcessing: true,
        bFilter: true,
        bServerSide: true,
        bJQueryUI: false,
        sPaginationType: 'full_numbers',
        sAjaxSource: '/product/category/filter',	
        sDom: '<"H"fl<"clear">>tr<"F"ip>',
        oLanguage: { sUrl: '/js/plugins/tables/lang_cn.txt'},
        aoColumnDefs: [
            { sTitle: "类别名称", aTargets: [0] },
            { sTitle: "排序", aTargets: [1] },
            { sTitle: "操作", aTargets: [2], bSearchable: false, sClass: 'tableActs', sWidth: 60 },
        ],
        fnRowCallback: function(nRow,aData, iDisplayIndex, iDisplayIndexFull) {
            var id = aData[2];
            var operation = '<a href="/product/category/edit?category_id=' + id + '" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon=""></span></a>' + 
                            '<a href="javascript:void(0);" data-id="' + id + '" action="delete" class="tablectrl_small bDefault tipS" original-title="删除"><span class="iconb" data-icon=""></span></a>';
            $('td:eq(2)', nRow).html(operation);
        },
        fnInitComplete: function() {
            $('.select_action, select[name$="list_table_length"],.checkAll').uniform();
        },
        fnDrawCallback: function() {
            $('#category_list_table').css('width', '100%');
        }
    });

    $('a[action="delete"]').live('click', function() {
        var category_id = $(this).attr('data-id');
        $.ajax({
            url: '/product/category/delete',
            type: 'POST',
            data: {category_id: category_id},
            dataType: 'json',
            success: function(data) {
                $.jGrowl(data.message);
                cTable.fnDraw();
            },
            error: function() {
                $.jGrowl('请求失败！');
            }
        });
    });

});
