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
            { sTitle: "<input id='checkAll' type='checkbox'/>", bSearchable: false, aTargets: [0], sWidth: '20px' },
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
            var checkbox = '<input type="checkbox" value="'+id+'" name="product_ids[]"/>';
            var price = aData[5] + ' ' + aData[4];
            var operation = '<a href="#" class="tablectrl_small bDefault tipS" original-title="同步"><span class="iconb" data-icon=""></span></a>' + 
                            '<a href="#" class="tablectrl_small bDefault tipS" original-title="发货"><span class="iconb" data-icon=""></span></a>' +
                            '<a href="#" class="tablectrl_small bDefault tipS" original-title="取消"><span class="iconb" data-icon=""></span></a>';

            $('td:eq(0)', nRow).html(checkbox);
            $('td:eq(3)', nRow).html(price);
            $('td:eq(8)', nRow).html(operation);
            $('td:eq(1)', nRow).attr('title', '双击查看订单详情').dblclick(function(){
                order_info(id, $(this).html());
            });
        },
        fnInitComplete: function(){
            $('.dataTables_info').css('clear', 'none').css('line-height', '34px');
            $('.tableFooter').prepend('<div class="itemActions" style="width: 250px">'+
                                '<label>批量操作:</label>'+
                                '<select name="action">'+
                                '    <option value="">--请选择--</option>'+
                                '    <option value="Edit">订单发货</option>'+
                                '    <option value="Delete">取消订单</option>'+
                                '    <option value="Move">同步订单</option>'+
                                '</select><a class="buttonS bDefault ml10">确定</a></div>'+
                                '</div>');
            $('select, #checkAll').uniform();
        },
        fnDrawCallback: function() {
            $('#order_list_table').css('width', '100%');
            $('#order_list_table :checkbox').not('#checkAll').uniform();
        }
        
    });

    // 列表全选
    $("#checkAll").live('click', function() {
        var checkedStatus = this.checked;
        $('#order_list_table tbody tr td:first-child input:checkbox').each(function(){
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
    $('#order_list_table tbody tr td:first-child input:checkbox').live('change', function() {
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

