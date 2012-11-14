$(function(){

    // 产品列表
    pTable = $('#product_list_table').dataTable({
        bSort: false,
        bProcessing: true,
        bFilter: true,
        bServerSide: true,
        bJQueryUI: false,
        sPaginationType: 'full_numbers',
        sAjaxSource: '/product/filter',
        sDom: '<"H"fl<"clear">>tr<"F"ip>',
        aoColumnDefs: [
                { sTitle: "<input id='checkAll' type='checkbox'/>",  aTargets: [0], bSearchable: false, sWidth: '20px' },
                { sTitle: "图片",  aTargets: [1], sWidth: '40px', bSearchable: false },
                { sTitle: "名称", aTargets: [2] } ,
                { sTitle: "SKU", aTargets: [3], sWidth: "100px"},
                { sTitle: "分类", aTargets: [4] , sWidth: "80px", bSearchable: false },
                { sTitle: "成本价", aTargets: [5], bSearchable: false, sWidth: "100px"},
                { sTitle: "价格范围", aTargets: [6], bSearchable: false, sWidth: "150px"},
                { bVisible: false, aTargets: [7] },
                { sTitle: '状态', aTargets: [8], sWidth: "100px", bSearchable: false },
                { bVisible: false, aTargets: [9], bSearchable: false },
                { sTitle: "操作", aTargets: [10], bSearchable: false, sClass: "tableActs", sWidth: "80px" }
                    ],
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            var id = aData[0];
            var checkbox = '<input type="checkbox" value="'+id+'" name="product_ids[]"/>';
            var image = '<a class="lightbox" target="_blank" href="' + aData[1] + '"><img src="' + aData[1] + '" style="width: 36px; height:36px;"/></a>';
            var limit = '$' + aData[6] + '~' + '$' + aData[7];
            var operation = '<a href="/product/edit/?product_id='+id+'" action="edit" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon=""></span></a>' + 
                            '<div class="btn-group">' +
                            '   <a data-id="'+id+'" action="multi-language" class="tablectrl_small bDefault tipS" original-title="多语言"><span class="iconb" data-icon=""></a>' +
                            '   <ul class="list-dropdown">' +
                            '       <li><a class="dataNumGrey hand">中</a></li>' +
                            '       <li><a class="dataNumGrey hand">英</a></li>' +
                            '   </ul>' +
                            '</div>' + 
                            '<a href="javascript:void(0);" data-id="'+id+'" action="delete" class="tablectrl_small bDefault tipS" original-title="删除"><span class="iconb" data-icon=""></span></a>';

            $('td:eq(0)', nRow).html(checkbox);
            $('td:eq(1)', nRow).html(image);
            $('td:eq(6)', nRow).html(limit);
            $('td:eq(7)', nRow).html(aData[8] == 1 ? '有效' : '失效');
            $('td:eq(8)', nRow).html(operation);
        },

        fnInitComplete: function() {
            $('select, #checkAll').uniform();
        },
        fnDrawCallback: function() {
            $('#product_list_table :checkbox').not('#checkAll').uniform();
            $('.tableActs .tipS').tipsy({gravity: 's',fade: true, html:true});
        },
        oLanguage: { sUrl: '/js/plugins/tables/lang_cn.txt' },
    });

    // 列表全选
    $("#checkAll").live('click', function() {
        var checkedStatus = this.checked;
        $('#product_list_table tbody tr td:first-child input:checkbox').each(function(){
            this.checked = checkedStatus;
            if (checkedStatus == this.checked) {
                $(this).closest('.checker > span').removeClass('checked');
                $(this).closest('table tbody tr').removeClass('thisRow');
            }
            if (this.checked) {
                $(this).closest('.checker > span').addClass('checked');
                $(this).closest('table tbody tr').addClass('thisRow');
            }
        });
    
    });

    // 列表单选效果
    $('#product_list_table tbody tr td:first-child input:checkbox').live('change', function() {
        $(this).closest('tr').toggleClass("thisRow", this.checked);
    });

    // 多语言
    $('a.tablectrl_small[action="multi-language"]').live('click', function(){
        var product_id = $(this).attr('data-id');
        var $this = $(this);
        $.ajax({
            url: '/product/language',
            type: 'POST',
            data: {product_id: product_id},
            dataType: 'json',
            success: function(data) {
                var li = '';
                for(i in data) {
                    if(data[i].exists) {
                        li += '<li><a href="/product/language/edit?language='+data[i].language+'&product_id='+product_id+'" class="dataNumGreen">' + data[i].name + '</a></li>';
                    } else {
                        li += '<li><a href="/product/language/add?language='+data[i].language+'&product_id='+product_id+'" class="dataNumGrey">' + data[i].name + '</a></li>';
                    }
                
                }
                $this.next().html(li);
            },
            error: function() {
                $.jGrowl('请求失败！'); 
            }
        });
        $(this).next().slideToggle(150);
    });
    // 产品删除操作
    $('a.tablectrl_small[action="delete"]').live('click', function(){
        var product_id = $(this).attr('data-id');
        $('#product_delete_confirm').attr('data-id', product_id).dialog('open');
    });




    // 删除dialog提示
    $('#product_delete_confirm').dialog({
        autoOpen: false, 
        width: 410,
        modal: true,
        buttons: {
                "取消": function() {
                    $(this).dialog( "close" );
                },
                "确定": function() {
                    var product_id = $('#product_delete_confirm').attr('data-id');
                    product_delete(product_id);
                    $(this).dialog( "close" );
                }
        }
    });

    // 编辑器
    $("#editor").cleditor({
		width:"100%", 
		height:"250px",
		bodyStyle: "margin: 10px; font: 12px Arial,Verdana; cursor:text",
		useCSS:true
	});

    // 图片操作显示
	$(".gallery ul li").live({'mouseenter': function() { $(this).children(".actions").show("fade", 200); }, 'mouseleave':function() { $(this).children(".actions").hide("fade", 200); }	});

    // 删除图片
    $("#images .remove").live('click', function(){
        $(this).parent().parent().remove();
    });

    // 删除
});

// 删除产品
function product_delete(product_id) {
    $.ajax({
        url: '/product/delete',
        type: 'POST',
        data: {product_id: product_id},
        dataType: 'json',
        success: function(data) {
            if(data.status == 'success') {
                $.jGrowl(data.message);
                pTable.fnDraw();
            } else if(data.status == 'fail') {
                $.jGrowl(data.message);
            } else {
                $.jGrowl('未知错误！');
            }
        },
        error: function() {
            $.jGrowl('删除请求失败！'); 
        }
    });
}
