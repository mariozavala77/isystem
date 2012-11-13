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
                { sTitle: "<input id='checkAll' type='checkbox'/>",  aTargets: [0], sWidth: '20px' },
                { sTitle: "图片",  aTargets: [1], sWidth: '40px', bServerSide: false },
                { sTitle: "名称", aTargets: [2] } ,
                { sTitle: "SKU", aTargets: [3], sWidth: "100px"},
                { sTitle: "分类", aTargets: [4] , sWidth: "80px" },
                { sTitle: "成本价", aTargets: [5], bSearchable: false, sWidth: "100px"},
                { sTitle: "价格范围", aTargets: [6], bSearchable: false, sWidth: "150px"},
                { bVisible: false, aTargets: [7] },
                { sTitle: '状态', aTargets: [8], sWidth: "100px" },
                { bVisible: false, aTargets: [9] },
                { sTitle: "操作", aTargets: [10], bSearchable: false, sClass: "tableActs", sWidth: "80px" }
                    ],
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            var id = aData[0];
            var checkbox = '<input type="checkbox" value="'+id+'" name="product_ids[]"/>';
            var image = '<a class="lightbox" target="_blank" href="' + aData[1] + '"><img src="' + aData[1] + '" style="width: 36px; height:36px;"/></a>';
            var limit = '$' + aData[6] + '~' + '$' + aData[7];
            var operation = '<a href="javascript:void(0);" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon=""></span></a>' + 
                            '<a href="javascript:void(0);" class="tablectrl_small bDefault tipS" original-title="多语言"><span class="iconb" data-icon=""></span></a>' + 
                            '<a href="javascript:void(0);" class="tablectrl_small bDefault tipS" original-title="删除"><span class="iconb" data-icon=""></span></a>';

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
