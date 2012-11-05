$(function(){
    pTable = $('#product_list_table').dataTable({
        bSort: false,
        bProcessing: true,
        bFilter: true,
        bServerSide: true,
        bJQueryUI: false,
        sPaginationType: 'full_numbers',
        sAjaxSource: '/product/filter',
        sDom: '<"H"fl>tr<"F"ip>',
        aoColumnDefs: [
                { sTitle: "图片",  aTargets: [0] },
                { sTitle: "名称", aTargets: [1] },
                { sTitle: "SKU", aTargets: [2] },
                { sTitle: "价格", aTargets: [3], bSearchable: false },
                { sTitle: "添加时间", aTargets: [4], bSearchable: false },
                { sTitle: "操作", "aTargets": [5], bSearchable: false, sClass: "textC tableToolbar", sWidth: '80px' }
                    ],
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            var id = aData[5];
            var operation = '<ul class="btn-group toolbar">' +
                           '    <li>' +
                           '        <a href="javascript:void(0);" data-id="' + id + '"  class="tablectrl_small bDefault edit">' +
                           '            <span class="iconb" data-icon=""></span>' +
                           '        </a>' +
                           '    </li>' +
                           '    <li>' +
                           '        <a href="javascript:void(0);" data-id="' + id + '" class="tablectrl_small bDefault delete">' +
                           '            <span class="iconb" data-icon=""></span>' +
                           '        </a>' +
                           '    </li>' +
                           '</ul>';

            $('td:eq(5)', nRow).html(operation);
        },
        oLanguage: {
            sSearch: '搜索:',
        }
    });

    $("#editor").cleditor({
		width:"100%", 
		height:"250px",
		bodyStyle: "margin: 10px; font: 12px Arial,Verdana; cursor:text",
		useCSS:true
	});
});
