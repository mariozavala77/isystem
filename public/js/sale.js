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
            var id = aData[4];
            var operation = '<a action="product_sell" sale_id="'+id+'" class="tablectrl_small bDefault tipS" original-title="上架"><span class="iconb" data-icon="&#xe15e"></span></a>'
                          + '<a href="/product/sale/edit?sale_id='+id+'" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon="&#xe1db"></span></a>' 
                          + '<a href="javascript: void(0);" data-id="'+id+'" class="tablectrl_small bDefault tipS" original-title="删除"><span class="iconb" data-icon="&#xe136"></span></a>';
            $('td:eq(4)', nRow).html(operation);
            $('td:eq(2)', nRow).html(aData[5]);
        },
        fnDrawCallback: function() {
            $('#sale_list_table').css('width', '100%');
        },
        fnInitComplete: function() {
            $('.select_action, select[name$="list_table_length"],.checkAll').uniform();
        }
    });

    // 上架对话框
    var product_sell_dialog = $('#product_sell_dialog'); 
    product_sell_dialog.dialog({
        autoOpen: false,
        width: '600px',
        modal: true,
    });

    // 打开上架对话框
    $('a[action="product_sell"]').live('click', function(){
        var sale_id = $(this).attr('sale_id');
        $.ajax({
            url: '/channel/sell',
            type: 'POST',
            data: {sale_id: sale_id},
            dataType: 'json',
            success: function(data) {
                if(data.length > 0) {
                    var channels = '';
                    for(i in data) {
                        var on_sale = '';
                        if(typeof(data[i].on_sale) != 'undefined' && data[i].on_sale != '0')
                            on_sale = '<em class="done" title="已上架"></em>';

                        // 清边框
                        style='';
                        if((i+1)%3 == 0) style='style="border-right: none"';
                            
                            
                        channels += '<li '+style+'>' + data[i].name + on_sale + '</li>' ;
                    }
                    product_sell_dialog.find('ul').html(channels);
                    product_sell_dialog.dialog('open');
                } else {
                    $.jGrowl('没有可以上架的渠道。');
                }
            },
            error: function() {
                $.jGrowl('请求失败!');
            }
        });
    });

});
