$(function(){
    initsearch = false;
    oTable = $('#order_list_table').dataTable({
        bSort: false,
        bProcessing: true,
        bFilter: true,
        bServerSide: true,
        bJQueryUI: false,
        sPaginationType: 'full_numbers',
        sAjaxSource: '/order/filter',
        sDom: '<"H"<"#olist_options"l<"clear">><"#olist_search"<"clear">>>tr<"F"ip>',
        oLanguage: { sUrl: '/js/plugins/tables/lang_cn.txt'},
        aoColumnDefs: [
            { aTargets: [0], bSearchable: false, sTitle: "<input class='checkAll' type='checkbox' key='order_list_table'/>",  sWidth: '20px' },
            { sTitle: "订单ID", aTargets: [1] }, // 订单ID
            { sTitle: "购买人", aTargets: [2] }, 
            { bVisible: false, aTargets: [3] },  // email
            { sTitle: "金额", aTargets: [4], sWidth: '60px' },
            { bVisible: false, aTargets: [5] },  // 货币
            { bVisible: false, aTargets: [6] },  // 收货姓名
            { bVisible: false, aTargets: [7] },  // 收货地址
            { bVisible: false, aTargets: [8] },  // 收货城市
            { bVisible: false, aTargets: [9] },  // 收货州/区
            { sTitle: "国家", aTargets: [10], sWidth: '30px' },
            { bVisible: false, aTargets: [11] }, // 邮编
            { bVisible: false, aTargets: [12] }, // 电话
            { sTitle: "来源", bSearchable: false, aTargets: [13], sWidth: '90px' },  // 来源
            { sTitle: "购买时间", aTargets: [14], sWidth: '120px' },
            { bVisible: false, aTargets: [15] }, // 支付方式
            { sTitle: "状态", aTargets: [16], bSearchable: false, sWidth: '40px' },
            { sTitle: "操作", aTargets: [17], bSearchable: false, sClass: "tableActs", sWidth: '80px' },
        ],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            var id = aData[0];
            var checkbox = '<input type="checkbox" value="'+id+'" name="order_ids[]"/>';
            var price = aData[5] + ' ' + aData[4];
            var operation = '<a href="#" class="tablectrl_small bDefault tipS" original-title="同步"><span class="iconb" data-icon=""></span></a>' + 
                            '<a href="#" class="tablectrl_small bDefault tipS" original-title="发货"><span class="iconb" data-icon=""></span></a>' +
                            '<a href="#" class="tablectrl_small bDefault tipS" original-title="取消"><span class="iconb" data-icon=""></span></a>';

            $(nRow).attr('id', 'oid'+id);
            $('td:eq(0)', nRow).html(checkbox);
            $('td:eq(1)', nRow).attr('title', '双击查看订单详情').dblclick(function(){
                order_info(id, $(this).html());
            });
            $('td:eq(3)', nRow).html(price);
            $('td:eq(8)', nRow).html(operation);
        },
        fnInitComplete: function() {
            $('.dataTables_info').css('clear', 'none').css('line-height', '34px');

            // 批量操作按钮
            $('.tableFooter').prepend('<input class="checkAll" type="checkbox" key="order_list_table"><div class="itemActions" style="width: 250px">'+
                                '<label>批量操作:</label>'+
                                '<select name="action">'+
                                '    <option value="">--请选择--</option>'+
                                '    <option value="ship">订单发货</option>'+
                                '    <option value="cancel">取消订单</option>'+
                                '    <option value="sync">同步订单</option>'+
                                '</select><a class="buttonS bDefault ml10" id="actions">确定</a></div>'+
                                '</div>');
            // 每页记录样式修改
            $('#order_list_table_length').addClass('mb15');
            // 美化dom元素
            $('select, .checkAll').uniform();

            // 初始化搜索div
            var search = '<div class="formRow">' +
                         '  <div class="grid1 textR">' +
                         '      <span>订单ID：</span>' +
                         '  </div>' +
                         '  <div id="filter_order_entity_id" class="grid2"></div>' +
                         '  <div class="grid1 textR">' +
                         '      <span>购买人：</span>' +
                         '  </div>' +
                         '  <div id="filter_order_name" class="grid2"></div>' +
                         '  <div class="clear"></div>' +
                         '</div>' + 
                         '<div class="formRow" style="border-bottom: 0">' +
                         '  <div class="grid1 textR">' +
                         '      <span>国家：</span>' +
                         '  </div>' +
                         '  <div id="filter_order_country" class="grid2"></div>' +
                         '  <div class="grid1 textR">' +
                         '      <span>来源：</span>' +
                         '  </div>' +
                         '  <div id="filter_order_source" class="grid2"></div>' +
                         '  <div class="clear"></div>' +
                         '</div>';
            $('#olist_search').html(search);
        },
        fnDrawCallback: function() {
            $('#order_list_table').css('width', '100%');
            $('#order_list_table :checkbox').not('.checkAll').uniform();
        }
        
    });

    // 列表全选
    $(".checkAll").live('click', function() {
        var checkedStatus = this.checked;

        // 指定范围
        var key = $(this).attr('key');

        // 多个全选按钮
        var multi = $('.checkAll[key="'+key+'"]').attr('checked', this.checked);
        if(this.checked)
            multi.parent().addClass('checked');
        else 
            multi.parent().removeClass('checked');

        $('#'+key+' tbody tr td:first-child input:checkbox').each(function(){
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
    $('tbody tr td:first-child input:checkbox').live('change', function() {
        $(this).closest('tr').toggleClass("thisRow", this.checked);
    });

    // 同步订单
    $('#sync').click(function(){
        $this = $(this);
        $.ajax({
            url: '/order/sync',
            type: 'POST',
            data: 'json',
            beforeSend: function() {
                $this.children('span').remove();
                $this.prepend('<img src="/images/elements/loaders/10s.gif" style="vertical-align: middle; margin-right: 8px">');
            },
            success: function(data) {
                $this.children('img').remove();
                $this.prepend('<span class="icos-refresh"></span>');
                if(data.status == 'success') {
                    $.jGrowl('同步成功！');
                } else {
                    $.jGrowl('同步失败！');
                }
            },
            error: function() {
                $.jGrowl('同步请求失败！');
            }
        });
    });

    // 批量操作
    $('#actions').live('click', function(){
        var action = $(this).prev().children('select').val();
        // 判断有无选操作
        if(action == '') {
            $.jGrowl('请先选择操作。');

            return false;
        }

        // 收集选中的订单
        var order_ids = new Array;
        $('input:checkbox[name="order_ids[]"]:checked').each(function(){
            order_ids[order_ids.length] = $(this).val();
        });

        // 判断有无选订单
        if(order_ids.length < 1) {
            $.jGrowl('请先选择你要操作的订单。');

            return false; 
        }

        // 批量发货
        if(action == 'ship') {
            $.ajax({
                url: '/order/batch',
                type: 'POST',
                data: {action: action, order_ids: order_ids},
                dataType: 'json',
                success: function(data) {
                    if(data.status == 'success') {
                        if(data.message.length < 1) {
                            $.jGrowl('没有需要操作的订单。');
                            return false;
                        }

                        if(order_ids.length != data.message.length) {
                            var num = order_ids.length - data.message.length;
                            $.jGrowl(num + '个订单已忽略。');
                        }
                        order_ids = data.message;

                        $('.shipRow').remove();
                        $('input[name="ship_company"]').val('');
                        $('input[name="ship_method"]').val('');

                        // 创建发货表单
                        var row = '<div class="formRow shipRow nopadding" style="border-bottom: 0; max-height: 487px; overflow:auto;">' +
                                  '    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="tLight noBorderT">' +
                                  '        <thead><tr><td width="50%">订单ID</td><td>跟踪号</td></tr></thead>' +
                                  '        <tbody>';
                        for(i in order_ids) {
                            row += '<tr>' +
                                   '    <td class="textC">' + $('#oid'+order_ids[i] +' td:eq(1)').html() + '</td>' +
                                   '    <td class="textC">' +
                                   '        <input type="text" style="width: 50%" name="ship_tracking_nos[]" />' +
                                   '        <input type="hidden" value="'+order_ids[i]+'" name="ship_order_ids[]" />' +
                                   '    </td>' +
                                   '</tr>';
                        }
                        row += '</tbody></table></div>';

                        var dialog = $('#order_batch_ship_dialog form');
                        dialog.children('div:first').append(row);

                        // 打开产品信息窗口
                        dialog.dialog({
                            autoOpen: false,
                            width: "80%",
                            modal: true,
                        });
                        dialog.dialog('open');
                    } else if(data.status == 'fail') {
                        $.jGrowl(data.message);
                    } else {
                        $.jGrowl('发货列表加载失败。');
                    }
                },
                error: function(){
                    $.jGrowl('批量操作请求失败。');
                }
            });
        }

    });

});

// 订单详情
function order_info(order_id, entity_id) {
    $('#order_info_dialog').attr('title', entity_id + '订单详情');

    // 获取产品信息
    $.ajax({
        url: '/order/info',
        type: 'POST',
        data: {order_id: order_id},
        dataType: 'json',
        success: function(data) {

            for(i in data){
                $('#order_info_dialog table tbody tr td[field="'+i+'"]').html(data[i]);
            }

            // 打开产品信息窗口
            $('#order_info_dialog').dialog({
                autoOpen: false,
                width: "80%",
                modal: true,
            });
            $('#order_info_dialog').dialog('open');
        
        },
        error: function(){
            $.jGrowl('获取产品信息失败！');
        }
    });
}
/*
 * 列隐藏显示
 var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
 oTable.fnSetColumnVis( iCol, bVis ? false : true );
 */

