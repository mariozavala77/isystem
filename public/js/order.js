$(function(){
    oTable = $('#order_list_table').dataTable({
        bSort: false,
        bProcessing: true,
        bFilter: true,
        bServerSide: true,
        bJQueryUI: false,
        sPaginationType: 'full_numbers',
        sAjaxSource: '/order/filter',
        sDom: '<"H"fl<"clear">>tr<"F"ip>',
        oLanguage: { sUrl: '/js/plugins/tables/lang_cn.txt'},
        aoColumnDefs: [
            { sTitle: "全选", aTargets: [0] },
            { sTitle: "订单ID", aTargets: [1] },
            { sTitle: "购买人", aTargets: [2] },
            { sTitle: "邮箱",  aTargets: [3] },
            { sTitle: "价格", aTargets: [4] },
            { bVisible: false, aTargets: [5] },
            { sTitle: "收货信息", aTargets: [6] },
            { bVisible: false, aTargets: [7] },
            { bVisible: false, aTargets: [8] },
            { bVisible: false, aTargets: [9] },
            { bVisible: false, aTargets: [10] },
            { bVisible: false, aTargets: [11] },
            { bVisible: false, aTargets: [12] },
            { sTitle: "来源", aTargets: [13] },
            { sTitle: "购买时间", aTargets: [14] },
            { sTitle: "支付方式", aTargets: [15] },
            { sTitle: "状态", aTargets: [16] },
            { sTitle: "操作", aTargets: [17], bSearchable: false, sClass: "tableActs" },
        ],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            var id = aData[0];
            var price = aData[5] + ' ' + aData[4];
            var shipping_info = '<ul>' +
                                    '<li>' + aData[6] + '</li>' +
                                    '<li>' + aData[7] + '</li>' +
                                    '<li>' + aData[8] + ' ' + aData[9] + '</li>' +
                                    '<li>' + aData[10] + '</li>' +
                                    '<li>Zip:' + aData[11] + '</li>' +
                                    '<li>Tel:' + aData[12] + '</li>' +
                                '</ul>';
            var operation = '<a href="#" class="tablectrl_small bDefault tipS" original-title="同步"><span class="iconb" data-icon=""></span></a>' + 
                            '<a href="#" class="tablectrl_small bDefault tipS" original-title="发货"><span class="iconb" data-icon=""></span></a>' +
                            '<a href="#" class="tablectrl_small bDefault tipS" original-title="取消"><span class="iconb" data-icon=""></span></a>';

            $('td:eq(4)', nRow).html(price);
            $('td:eq(5)', nRow).html(shipping_info);
            $('td:eq(10)', nRow).html(operation);
        }
        
    });
})
/*
 var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
 oTable.fnSetColumnVis( iCol, bVis ? false : true );
 */

