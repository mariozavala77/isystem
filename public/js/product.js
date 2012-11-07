$(function(){
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
                { sTitle: "图片",  aTargets: [0] },
                { sTitle: "名称", aTargets: [1] },
                { sTitle: "SKU", aTargets: [2] },
                { sTitle: "价格", aTargets: [3], bSearchable: false },
                { sTitle: "添加时间", aTargets: [4], bSearchable: false },
                { sTitle: "操作", "aTargets": [5], bSearchable: false, sClass: "tableActs", sWidth: '80px' }
                    ],
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            var id = aData[5];
            var operation = '<a href="#" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon=""></span></a>' + 
                            '<a href="#" class="tablectrl_small bDefault tipS" original-title="删除"><span class="iconb" data-icon=""></span></a>';

            $('td:eq(5)', nRow).html(operation);
        },
        oLanguage: { sUrl: '/js/plugins/tables/lang_cn.txt' },
    });

    $("#editor").cleditor({
		width:"100%", 
		height:"250px",
		bodyStyle: "margin: 10px; font: 12px Arial,Verdana; cursor:text",
		useCSS:true
	});
});
