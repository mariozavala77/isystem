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
        aoColumnDefs: [{
            sTitle: "全选",
            aTargets: [0],
            bSearchable: false,
        },
        {
            sTitle: "标题",
            aTargets: [1]
        },
        {
            sTitle: "SKU",
            aTargets: [2],
            bSearchable: false,
        },
        {
            sTitle: "价格",
            aTargets: [3],
            bSearchable: false,
        },
        {
            sTitle: "渠道",
            aTargets: [4],
            bSearchable: false,
        },
        {
            sTitle: "代理商",
            aTargets: [5],
            bSearchable: false,
        },
        {
            sTitle: "上架",
            aTargets: [6],
            bSearchable: false,
        },
        {
            sTitle: "操作",
            aTargets: [7],
            bSearchable: false,
            sClass: 'tableActs'
        },
        ],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            var id = aData[7];
            var operation = '<a href="/product/category/edit?category_id=' + id + '" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon=""></span></a>' + '<a href="javascript: void(0);" data-id="' + id + '" class="tablectrl_small bDefault tipS" original-title="删除"><span class="iconb" data-icon=""></span></a>';
            $('td:eq(7)', nRow).html(operation);
            $('td:eq(4)', nRow).html(aData[8]);
            $('td:eq(5)', nRow).html(aData[9]);
            $('td:eq(6)', nRow).html(sold[aData[6]]);
        }
    });

});