var status = ["\u672a\u5ba1\u6838", "\u5df2\u901a\u8fc7", "\u672a\u901a\u8fc7"];
var sold = ["\u672a\u5904\u7406", "\u5df2\u53d1\u8d27", "\u5df2\u4e0b\u67b6"];
$(function() {

    // 初始化搜索标识
    initsearch = false;

    // 在售列表
    sTable = $('#sale_list_table').dataTable({
        bSort: false,
        bProcessing: true,
        bFilter: true,
        bServerSide: true,
        bJQueryUI: false,
        sPaginationType: 'full_numbers',
        sAjaxSource: '/product/sale/filter',
        sDom: '<"H"<"#slist_options"l<"clear">><"#slist_search"<"clear">>>tr<"F"ip>',
        oLanguage: {
            sUrl: '/js/plugins/tables/lang_cn.txt'
        },
        aoColumnDefs: [
        {
            sTitle: "标题",
            aTargets: [0],
        },
        {
            sTitle: "SKU",
            aTargets: [1],
            sWidth: '10%',
        },
        {
            sTitle: "价格",
            aTargets: [2],
            sWidth: '30px',
        },
        {
            sTitle: "渠道",
            aTargets: [3],
            sWidth: '10%',
        },
        {
            sTitle: "代理商",
            aTargets: [4],
            sWidth: '10%',
        },
        {
            sTitle: "状态",
            aTargets: [5],
            sWidth: '30px',
        },
        {
            sTitle: "认购时间",
            aTargets: [6],
            bSearchable: false,
            sWidth: '120px',
        },
        {
            sTitle: "操作",
            aTargets: [7],
            bSearchable: false,
            sClass: 'tableActs',
            sWidth: '80px',
        },
        ],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            var id = aData[7];
            var status = aData[5];
            var operation = '<a action="product_sell" id="'+id+'" class="tablectrl_small bDefault tipS" original-title="上架"><span class="iconb" data-icon="&#xe15e"></span></a>'
                          + '<a href="/product/sale/edit?id='+id+'" class="tablectrl_small bDefault tipS" original-title="编辑"><span class="iconb" data-icon="&#xe1db"></span></a>' 
                          + '<a href="javascript: void(0);" data-id="'+id+'" class="tablectrl_small bDefault tipS" original-title="删除"><span class="iconb" data-icon="&#xe136"></span></a>';

            var status_html = '已下架';
            if(status == 1) {
                status_html = '<span class="green">已上架</span>';
            }
            
            $('td:eq(5)', nRow).html(status_html);
            $('td:eq(7)', nRow).html(operation);
        },
        fnDrawCallback: function() {
            $('#sale_list_table').css('width', '100%');
        },
        fnInitComplete: function() {
            $('.select_action, select[name$="list_table_length"],.checkAll').uniform();
            

            // 每页记录样式修改
            $('#sale_list_table_length').css('margin-bottom', '8px');

            var search = '<div class="formRow">' +
                         '  <div class="grid1 textR">' +
                         '      <span>标题：</span>' +
                         '  </div>' +
                         '  <div id="filter_sale_title" class="grid3"></div>' +
                         '  <div class="grid1 textR">' +
                         '      <span>SKU：</span>' +
                         '  </div>' +
                         '  <div id="filter_sale_sku" class="grid2"></div>' +
                         '  <div class="clear"></div>' +
                         '</div>' +
                         '<div class="formRow">' +
                         '  <div class="grid1 textR">' +
                         '      <span>渠道：</span>' +
                         '  </div>' +
                         '  <div id="filter_sale_channel" class="grid1"></div>' +
                         '  <div class="grid1 textR">' +
                         '      <span>代理：</span>' +
                         '  </div>' +
                         '  <div id="filter_sale_agent" class="grid1"></div>' +
                         '  <div class="grid1 textR">' +
                         '      <span>状态：</span>' +
                         '  </div>' +
                         '  <div id="filter_sale_status" class="grid1"></div>' +
                         '  <div class="clear"></div>' +
                         '</div>' +
                         '<div class="formRow" style="border-bottom: none">' +
                         '  <div class="grid12 textC">' +
                         '      <a class="buttonS bDefault" id="sale_search_reset">重置</a>' +
                         '      <a class="buttonS bDefault" id="sale_search">搜索</a>' +
                         '  </div>' +
                         '  <div class="clear"></div>' +
                         '</div>';

            $('#slist_search').html(search);
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
        var sale_sku_id = $(this).attr('id');
        $.ajax({
            url: '/channel/sell',
            type: 'POST',
            data: {sale_sku_id: sale_sku_id},
            dataType: 'json',
            success: function(data) {
                if(data.length > 0) {
                    var channels = '';
                    for(i in data) {
                        var on_sale = '';
                        if(typeof(data[i].on_sale) != 'undefined' && data[i].on_sale != '0')
                            on_sale = '<em class="on_sale" title="已上架"></em>';

                        
                        if(on_sale != '')
                            var addtional = ' channel_id="'+data[i].id+'" sale_id="'+data[i].on_sale+'" on_sale="true"';
                        else
                            var addtional = ' channel_id="'+data[i].id+'" sale_id="'+sale_sku_id+'" on_sale="false"';

                        // 清边框
                        if((i+1)%3 == 0) addtional += ' style="border-right: none"';

                        channels += '<li'+addtional+'>' + data[i].name + on_sale + '</li>' ;
                    }
                    product_sell_dialog.find('ul').html(channels);
                    product_sell_dialog.dialog('open');呢
                } else {
                    $.jGrowl('没有可以上架的渠道。');
                }
            },
            error: function() {
                $.jGrowl('请求失败!');
            }
        });
    });

    // 编辑器
    $("#editor").cleditor({
		width:"100%", 
		height:"250px",
		bodyStyle: "margin: 10px; font: 12px Arial,Verdana; cursor:text",
		useCSS:true
	});

    // 点击上架
    $('li[on_sale="false"]').live('click', function(){
        alert(1);
    });

});
