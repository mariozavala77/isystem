$(document).ready(function(){
    $('a[action="order_ship_submit"]').click(function(){
        var data = $('div[field="items_ship"] input').serialize();
        $.ajax({
            url: '/order/ship',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(data) {
                if(data.status == 'success') {
                    order_ship_dialog.dialog('close');
                    $.jGrowl('发货成功！');
                    location.reload();
                } else if(data.status == 'fail') {
                    $.jGrowl(data.message);
                
                } else {
                    $.jGrowl('未知错误！');
                }
            },
            error: function() {
                $.jGrowl('发货失败！');
            }
        });
    });
    $('#order_ship_dialog').dialog({
        autoOpen: false,
        width: "90%",
        modal: true,
    });
    $('#sync_dialog').dialog({
        autoOpen: false,
        resizable:false,
        modal: true,
        closeOnEscape: false
    });
    $('#order_cannel').live('click', function(){
        $('#order_cancel_dialog').dialog('open');
    });
    // 绑定同步
    $('#sync').bind('click', sync);
    $('#order_cancel_dialog').dialog({
        autoOpen: false,
        width: 400,
        modal: true,
        buttons: {
            "取消": function() {
                $(this).dialog("close");
            },
            "确定": function() {
                order_cancel(entity_id);
                $(this).dialog('close');
            }
        }
    });    
});

// 订单详情
function get_order_info(){
    if(!datainfo){
        $.post('/order/info', {order_id: entity_id},function(response){
            for(i in response){
                $('#order_info_dialog table tbody tr td[field="'+i+'"]').html(response[i]);
            }
            datainfo = response;
        },'json');
    }else{
        for(i in datainfo){
            $('#order_info_dialog table tbody tr td[field="'+i+'"]').html(datainfo[i]);
        }
    }
}

// 订单发货
function order_ship() {
    if(datainfo){
        for(i in datainfo) {
            if(i=='items_ship'){
                $('#order_ship_dialog').find('[field="'+i+'"]').html(datainfo[i]+'<input type="hidden" name="task_id" value="'+task_id+'">');
            }else{
                $('#order_ship_dialog').find('[field="'+i+'"]').html(datainfo[i]);
            }
        }
        $('.order_ship_company').uniform();
        $('#order_ship_dialog').dialog('open');
    }else{
        $.ajax({
            url: '/order/info',
            type: 'POST',
            data: {order_id: entity_id},
            dataType: 'json',
            success: function(data) {
                datainfo = data;
                for(i in data) {
                    if(i=='items_ship'){
                        $('#order_ship_dialog').find('[field="'+i+'"]').html(datainfo[i]+'<input type="hidden" name="task_id" value="'+task_id+'">');
                    }else{
                        $('#order_ship_dialog').find('[field="'+i+'"]').html(datainfo[i]);
                    }
                }
                $('.order_ship_company').uniform();
                $('#order_ship_dialog').dialog('open');
            },
            error: function() {
                $.jGrowl('获取发货详情失败！');
            }
        });
    }
}
// 订单取消
function order_cancel(order_id) {
    // 提交取消订单ID
    $.ajax({
        url: '/order/cancel',
        type: 'POST',
        data: {order_id: order_id},
        dataType: 'json',
        success: function(data) {
            if(data.status == 'success') {
                $.jGrowl('取消成功！');
                location.reload();
            } else if(data.status == 'fail'){
                $.jGrowl('操作失败！');
            } else {
                $.jGrowl('未知错误！');
            }
        },
        error: function() {
            $.jGrowl('订单取消请求失败。');
        }
    });
}
// 同步订单
var sync = function(){
    $('#sync').unbind('click');
    $.ajax({
        url: '/order/sync',
        type: 'POST',
        data: 'json',
        beforeSend: function() {
            $('#sync_dialog').dialog('open');
        },
        success: function(data) {
            $('#sync_dialog').dialog('close');
            if(data.status == 'success') {
                $.jGrowl('同步成功！');
                //oTable.fnDraw();
            } else {
                $.jGrowl('同步失败！');
            }
            $this.bind(sync);
        },
        error: function() {
            $.jGrowl('同步请求失败！');
            $this.bind(sync);
        }
    });
}