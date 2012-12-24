var data_id = 0;
var parent_id = 0;
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
            { sTitle: "操作", aTargets: [2], bSearchable: false, sClass: 'tableActs', sWidth: 80 },
        ],
        fnRowCallback: function(nRow,aData, iDisplayIndex, iDisplayIndexFull) {
            var id = aData[2];
            var operation = '<a href="javascript:void(0);" class="tablectrl_small bDefault tipS" original-title="添加分类" data-id="' + id + '" title="添加分类" action="add_category"><span class="iconb" data-icon="&#xe14a;"></span></a>'+
                            '<a href="/product/category/edit?category_id=' + id + '" class="tablectrl_small bDefault tipS" original-title="编辑" title="编辑分类"><span class="iconb" data-icon=""></span></a>' + 
                            '<a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault tipS" original-title="删除" action="delete" title="删除分类"><span class="iconb" data-icon=""></span></a>';
            $('td:eq(2)', nRow).html(operation);
        },
        fnInitComplete: function() {
            $('.select_action, select[name$="list_table_length"],.checkAll').uniform();
        },
        fnDrawCallback: function() {
            $('#category_list_table').css('width', '100%');
        }
    });
    $('a[action="delete"]').live('click', function(){
        data_id = $(this).attr('data-id');
        $('#category_delete_confirm').dialog('open');
    });
    $('a[action="add_category"]').live('click', function(){
        parent_id = $(this).attr('data-id');
        $('#category_add_confirm').dialog('open');
    });
    $('#category_delete_confirm').dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        buttons: {
            "取消": function() {
                $(this).dialog("close");
            },
            "确定": function() {
                del_category(data_id);
                $(this).dialog('close');
            }
        }
    });
    $('#category_add_confirm').dialog({
        autoOpen: false,
        height: 162,
        width: 314,
        modal: true,
        buttons: {
            "取消": function() {
                $(this).dialog("close");
            },
            "确定": function() {
                add_category(parent_id);
            }
        }
    });
    $('#add_category').click(function(){
        parent_id = 0;
        $('#category_add_confirm').dialog('open');
    });
});

function del_category(category_id){
    $.post('/product/category/delete',{category_id:category_id},function(data){
        $('#category_delete_confirm').dialog("close");
        if(empty(data)){
            $.jGrowl('请求操作错误。');
            return false;
        }
        if(data.status=='success'){
            cTable.fnDraw();
            return false;
        }
    },'json');
}

function add_category(parent_id){
    var category_name = $('#category').val();
    if(empty(category_name)){
        $.jGrowl('请填写分类名称');
        return false;
    }
    $.post('/product/category/insert/',{parent_id:parent_id,name:category_name},function(data){
        if(empty(data)){
            $.jGrowl('请求操作错误。');
            $('#category_add_confirm').dialog('close');
            return false;
        }
        if(data.status=='success'){
            $('#category_add_confirm').dialog('close');
            cTable.fnDraw();
            return false;
        }else{
            $.jGrowl('分类名称有重复');
            return false;
        }
    },'json');
}
