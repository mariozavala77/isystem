var status = ["\u672a\u5ba1\u6838", "\u5df2\u901a\u8fc7", "\u672a\u901a\u8fc7"];
var sold = ["\u672a\u5904\u7406", "\u5df2\u53d1\u8d27", "\u5df2\u4e0b\u67b6"];
$(function() {
    sTable = $('#sale_list_table').dataTable({
        bSort: false,
        bProcessing: true,
        bFilter: true,
        bServerSide: true,
        bJQueryUI: false,
        sPaginationType: 'full_numbers',
        sAjaxSource: '/product/sale/filter',
        sDom: '<"H"fl<"clear">>tr<"F"ip>',
        oLanguage: {
            sUrl: '/js/plugins/tables/lang_cn.txt'
        },
        aoColumnDefs: [
        {
            sTitle: "标题",
            aTargets: [0],
            sWidth: '60%',
        },
        {
            sTitle: "价格",
            aTargets: [1],
            bSearchable: false,
        },
        {
            sTitle: "代理商",
            aTargets: [2],
            bSearchable: false,
        },
        {
            sTitle: "认购时间",
            aTargets: [3],
            bSearchable: false,
        },
        {
            sTitle: "操作",
            aTargets: [4],
            bSearchable: false,
            sClass: 'tableActs'
        },
        ],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            var id = aData[0];
            var operation = '<a href="/product/sale/edit?sale_id=' + id + '" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon=""></span></a>' + '<a href="javascript: void(0);" data-id="' + id + '" class="tablectrl_small bDefault tipS" original-title="删除"><span class="iconb" data-icon=""></span></a>';
            $('td:eq(4)', nRow).html(operation);
            $('td:eq(2)', nRow).html(aData[5]);
        }
    });

});
