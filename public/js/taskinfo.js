var datainfo;
$(document).ready(function() {
get_task_message();
get_entity_info();
    $('#sendMessage').click(function(){
        push_messge();
    });
    $('#agent_examine').click(function(){
        $('#agent_info_examine').dialog('open');
    });
    $('#agent_sale').click(function(){
        alert('销售商品');
    });
    $('#agent_off').click(function(){
        alert('产品下架');
    });
    $('input[name="info_status"]').change(function(){
        if($(this).val()==2){
            $('#examine_content_form').show();
        }else{
            $('#examine_content_form').hide();
        }
    });
    $('#agent_info').dialog({
        autoOpen: false,
        modal: true,
        width:'500px',
        resizable:false,
        buttons: {
            "取消": function () {
                $(this).dialog("close");
            }
        }
    });
    $('#task_finish_confirm').dialog({
        autoOpen: false,
        resizable:false,
        modal: true,
        buttons: {
            "确定": function () {
                handle();
            },
            "取消": function () {
                $(this).dialog("close");
            }
        }
    });
    $('#task_confirm').dialog({
        autoOpen: false,
        resizable:false,
        modal: true,
        buttons: {
            "提交": function () {
                push_task();
            },
            "取消": function () {
                $(this).dialog("close");
            }
        }
    });
    $('#handle').click(function(){
        task_handle = 1;
        $('#task_finish').html('<p>你确认标记此任务已完成?</p>').dialog('open');
    });
    $('#open').click(function(){
        task_handle = 0;
        $('#task_finish').html('<p>你确认要重新打开这个此任务吗?</p>').dialog('open');
    });
    $('#bulidtask').click(function(){
       $('#task_confirm').dialog('open'); 
    });
    $( "#task_level" ).val(0);
    $( ".uMin" ).slider({ /* Slider with minimum */
        range: "min",
        value: 0,
        min: 0,
        max: 9,
        slide: function( event, ui ) {
            $( "#task_level" ).val(ui.value );
        }
    });
    $('#task_finish').dialog({
        autoOpen: false,
        resizable:false,
        modal: true,
        buttons: {
            "确认": function () {
                $(this).dialog("close");
                $('#task_finish_confirm').dialog('open');
            },
            "取消": function () {
                $(this).dialog("close");
            }
        }
    });
    $('#agent_info_examine').dialog({
        autoOpen: false,
        resizable:false,
        modal: true,
        buttons: {
            "确定": function () {
                agent_info_pass();
            },
            "取消": function () {
                $(this).dialog("close");
            }
        }
    });
});
/**
 * 获取任务留言的信息
 */
function get_task_message(){
    $.post('/task/comment/filter',{taskid:task_id},function(response){
        if(!empty(response)){
            var html = buildmessge(response);
        }else{
            var html = '<li style="text-align:center;padding-top:10px;">暂时没有备注</li>';
        }
        $('#msg_lists').html(html);
    },'json');
}

/**
 * 动态创建留言的html结构
 *
 * @param: data object 留言的内容
 */
function buildmessge(data){
    var html = new Array();
    var length = data.length;
    for(var i=0;i<length;i++){
        var msg = data[i];
        html.push('<li class="');
        if(msg.uid==user_id){
            html.push('by_me');
        }else{
            html.push('by_user');
        }
        html.push('"><div class="messageArea" style="margin:0px"><div class="infoRow"><span class="name"><strong>');
        html.push(msg.username);
        html.push('</strong> says:</span><span class="time">');
        html.push(timedesc(nowtime, msg.created_at)+'</span><div class="clear"></div></div>');
        html.push(msg.comment);
        html.push('</div><div class="clear"></div></li>');
    }

    return html.join('');
}
/**
 * 两个时间戳的间隔
 * 返回类似腾讯微博
 *
 * @param: nowtime   integer 当前的时间戳
 * @param: timestamp integer 需要对比的时间戳
 */
function timedesc(nowtime, timestamp) {
    var diff = nowtime - timestamp;
    var nowtimeDate = new Date(nowtime * 1000);
    var previousDayDate = new Date((nowtime - 24 * 3600) * 1000);
    var timestampDate = new Date(timestamp * 1000);
    var timestampYear = timestampDate.getFullYear();
    var timestampMonth = timestampDate.getMonth();
    var timestampDay = timestampDate.getDate();
    var timestampHours = timestampDate.getHours() < 10 ? "0" + timestampDate.getHours() : timestampDate.getHours();
    var timestampMinutes = timestampDate.getMinutes() < 10 ? "0" + timestampDate.getMinutes() : timestampDate.getMinutes();
    if (diff < 60) {
        return "刚刚"
    } else {
        if (diff < 3600) {
            return Math.floor(diff / 60) + "分钟前"
        }
    }
    if (nowtimeDate.getFullYear() == timestampYear) {
        if (nowtimeDate.getMonth() == timestampMonth) {
            if (nowtimeDate.getDate() == timestampDay) {
                return "今天" + timestampHours + ":" + timestampMinutes
            } else {
                if (previousDayDate.getDate() == timestampDay) {
                    return "昨天" + timestampHours + ":" + timestampMinutes
                } else {
                    return [timestampMonth + 1, "月", timestampDay, "日", " ", timestampHours, ":", timestampMinutes].join("")
                }
            }
        } else {
            return [timestampMonth + 1, "月", timestampDay, "日", " ", timestampHours, ":", timestampMinutes].join("")
        }
    } else {
        return [timestampYear, "年", timestampMonth + 1, "月", timestampDay, "日", " ", timestampHours, ":", timestampMinutes].join("")
    }
}
/**
 * 判断参数的值是否为空
 */
function empty (mixed_var) {

  var undef, key, i, len;
  var emptyValues = [undef, null, false, 0, "", "0"];

  for (i = 0, len = emptyValues.length; i < len; i++) {
    if (mixed_var === emptyValues[i]) {
      return true;
    }
  }

  if (typeof mixed_var === "object") {
    for (key in mixed_var) {
      return false;
    }
    return true;
  }

  return false;
}

/**
 * 获取产品的相信信息
 *
 * @entity_id: nowtime   integer entity_id
 */
function get_product_info(entity_id){
    var local_key = 'product_info_'+entity_id;
    var data = localStorage.getItem(local_key);
    if(data){
        $('#info').html(data);
        $(".lightbox").fancybox({
            'padding': 2
        });
    }else{
        $.post('/product/info',{product_id:entity_id},function(response){
            if(response.status=='fail'){
                $.jGrowl(response.message);
            }else{
                data = bulid_product_html(response.message);
                localStorage.setItem(local_key, data);
                $('#info').html(data);
                $(".lightbox").fancybox({
                    'padding': 2
                });
            }
        },'json');
    }
}

/**
 * 绘制产品详细信息的页面
 */
function bulid_product_html(data){
    var html = new Array();
    html.push('<table class="tDefault" width="100%"><tbody><tr>');
    html.push('<td>商品名称：</td><td colspan="5">'+data.name+'</td>');
    html.push('</tr><tr><td>SKU：</td><td>'+data.sku+'</td>');
    html.push('<td>类别：</td><td>'+data.category+'</td>');
    html.push('<td>开发者：</td><td>'+data.devel+'</td>');
    html.push('</tr><tr><td>供应商：</td><td colspan="4">'+data.supplier+'</td>');
    html.push('</tr><tr><td>成本价：</td><td>'+data.cost+'</td>');
    html.push('<td>认领价：</td><td>'+data.price+'</td>');
    html.push('<td>售价范围：</td><td>'+data.min_price+'-'+data.max_price+'</td></tr>');
    if(!empty(data.stock)){
        var stocks = data.stock;
        html.push('<tr><td colspan="6" align="center">商品库存</td></tr><tr><td align="center">SKU</td><td align="center">可售数量</td><td align="center">不可售数量</td><td align="center">仓储类型</td><td colspan="2" align="center">所在区域</td></tr>');
        var length = stocks.length;
        for (var i = 0; i < length; i++) {
            var stock = stocks[i];
            html.push('<tr><td align="center">'+stock.code+'</td>');
            html.push('<td align="center">'+stock.sellable+'</td>');
            html.push('<td align="center">'+stock.unsellable+'</td>');
            html.push('<td align="center">'+stock.type+'</td>');
            html.push('<td colspan="3" align="center">'+stock.area+'</td></tr>');
        }
    }
    html.push('<tr><td>关键词：</td><td colspan="3">'+data.keywords+'</td><td>重量：</td><td>'+data.weight+'</td></tr>');
    html.push('<tr><td>短描述：</td><td colspan="3">'+data.short_description+'</td><td>尺寸：</td><td>'+data.size+'</td></tr>');
    if(!empty(data.images)){
        html.push('<tr><td colspan="6" align="center">商品图库</td></tr><tr><td colspan="6" align="center" style="padding-left:0px;"><ul class="gallery nopadding">');
        var imgs = data.images;
        var imgln = imgs.length;
        for (var i = 0; i < imgln; i++) {
            var img = imgs[i];
            html.push('<li style="float:left;margin-left:11px;"><a href="'+img.url+'" title="'+img.image+'" class="lightbox" rel="group"><img src="'+img.url+'" alt="'+img.image+'" width="80" hight="80"></a></li>');
        }
        html.push('</ul></td></tr>');
    }
    html.push('<tr><td colspan="6" align="center">商品详情</td></tr>');
    html.push('<tr><td colspan="6">'+data.description+'</td></tr>');
    html.push('</tbody></table>');
    return html.join('');
}
/**
 * 发送任务留言
 */
function push_messge(){
    var msg = $('#enterMessage').val();
    if(empty(msg)){
        $.jGrowl('请填写备注信息');
        $('#enterMessage').focus();
        return false;
    }
    $.post('/task/comment/insert',{task_id:task_id,comment:msg},function(response){
        if(response.status=='fail'){
            $.jGrowl(response.message);
            $('#enterMessage').focus();
            return false;
        }
        $('#enterMessage').val('').focus();
        $('#msg_lists').html(buildmessge(response.message));
    },'json');
}

function handle(){
    var msg = $('#message').val();
    if(empty(msg)){
        $('#message').focus();
        $.jGrowl('请填写备注信息！');
        return false;
    }
    $('#task_finish_confirm').dialog("close");
    $.post('/task/handle',{task_id:task_id,handle:task_handle,comment:msg},function(response){
        $.jGrowl(response.message);
        if(response.status=='success'){
            $('#message').val('');
            location.reload();
        }
    },'json');
}

function push_task(){
    var task_type = $('#task_type option:selected').val();
    var task_entity_id = $('#task_entity_id').val();
    var task_level = $('#task_level').val();
    var task_to_uid =$('#task_to_uid option:selected').val();
    var task_content =$('#task_content').val();
    var task_channel = $('input[name="task_channel"]:checked').val();
    if (task_channel==0) {
        if(empty(task_entity_id)){
            $.jGrowl('请填写实际id！');
            $('#task_level').focus();
            return false;
        }
    }
    if(empty(task_content)){
        $.jGrowl('请填写任务内容！');
        $('#task_content').focus();
        return false;
    }

    task_entity_id = (task_channel==0)?entity_id:task_entity_id;
    var parent_id = (task_channel==0)?task_id:0;

    $.post('/task/insert',{to_uid:task_to_uid,parent_id:parent_id,type:task_type,entity_id:task_entity_id,content:task_content,level:task_level},function(response){
        $('#task_confirm').dialog('close');
            $.jGrowl(response.message);
    },'json');
}
function get_entity_info(){
    switch(task_mod){
        case 'product':
            get_product_info(entity_id);
        break;
        case 'product_sale':
            get_product_sale_info();
        break;
        case 'order':
            get_order_info();
        break;
    }
}

function get_product_sale_info(){
    var local_key = 'product_sale_info_'+entity_id;
    datainfo = localStorage.getItem(local_key);
    if(datainfo){
        alert(localStorage.getItem('haha'));
        $('#info').html(bulid_product_sale_info(datainfo));
    }else{
        $.post('/product/sale/info',{sale_id:entity_id},function(response){
            if(response.status=='fail'){
                $.jGrowl(response.message);
                return false;
            }
            datainfo = response.message;
            //localStorage.setItem(local_key, '\''+datainfo+'\'');
            $('#info').html(bulid_product_sale_info(datainfo));
        },'json');
    }
}


function bulid_product_sale_info(data){
    var html = new Array();
    html.push('<table class="tDefault" width="100%"><tbody>');
    var agent = data.agent;
    if(agent){
        bulid_agent_info(agent);
    }
    html.push('<tr><td colspan="6" align="center">代理商产品信息</td></tr>');
    var validation = data.validation;
    html.push('<tr><td>商品名称：</td><td colspan="5">'+(empty(validation.title)?data.title:validation.title)+'</td></tr>');
    html.push('<tr><td>代理商:</td><td colspan="5"><a href="javascript:void(0);" onclick="agent_info('+data.agent_id+')" title="点击查看代理商详细信息" class="tipS">'+agent.company+'<span class="icos-info"></span></a></td></tr>');
    html.push('<tr><td>SKU：</td><td>'+data.sku+'</td><td>单价：</td><td>'+(empty(validation.price)?data.price:validation.price)+'</td>');
    html.push('<td>审核：</td><td>'+(data.status==1?'通过':(data.status==2?'未通过':'未审核'))+'</td></tr>');
    html.push('<tr><td>关键词：</td><td colspan="3">'+(empty(validation.keywords)?data.keywords:validation.keywords)+'</td>');
    if(data.sold==0){
        html.push('<td>上架状态：</td><td>未处理</td>');
    }else if(data.sold==1){
        html.push('<td>上架时间：</td><td>'+data.sold_at+'</td>');
    }else{
        html.push('<td>下架时间：</td><td>'+data.solad_at+'</td>');
    }
    html.push('</tr><tr><td>短描述：</td><td colspan="5">');
    html.push(empty(validation.short_description)?data.short_description:validation.short_description);
    html.push('</td></tr>');
    html.push('<tr><td colspan="6" align="center">产品详细描述</td></tr><tr><td colspan="6">');
    html.push(empty(validation.description)?data.description:validation.description);
    html.push('</td></tr>');
    html.push('</tbody></table>');
    return html.join('');
}

function bulid_agent_info(agent){
    var html = new Array();
    html.push('<table class="tDefault" width="100%"><tbody>');
    html.push('<tr><td colspan="6" align="center">代理商信息</td></tr>');
    html.push('<tr><td align="center">公司名称：</td><td colspan="3">'+agent.company+'</td><td align="center">公司电话:</td><td>'+agent.phone+'</td><tr/>');
    html.push('<tr><td align="center">公司地址：</td><td colspan="3">'+agent.address+'</td><td align="center">电子邮件:</td><td>'+agent.email+'</td><tr/>');
    html.push('</tbody></table>');
    $('#agent_info').html(html.join(''));
}

function agent_info(){
    $('#agent_info').dialog('open');
}

function agent_info_pass(){
    var status  = $('input[name="info_status"]:checked').val();
    var comment = $('#examine_content').val();
    if(empty(status)){
        $.jGrowl('请选择信息的状态');
        return false;
    }

    if(empty(comment) && status ==1){
        $.jGrowl('请填写不通过的原因');
        return false;
    }
    $('#agent_info_examine').dialog("close");
    $.post('/product/sale/check',{task_id:task_id,sale_id:entity_id,status:status,msg:comment},function(response){
        $.jGrowl(response.message);
        if(response.status=='success'){
            location.reload();
        }
    },'json');
}

function agent_sale(){
    $.post('/product/sale/sale',{task_id:task_id,sale_id:entity_id},function(response){
        $.jGrowl(response.message);
        if(response.status=='success'){
            location.reload();
        }
    },'json');
}

function agent_off(){
    $.post('/product/sale/off',{task_id:task_id,sale_id:entity_id},function(response){
        $.jGrowl(response.message);
        if(response.status=='success'){
            location.reload();
        }
    },'json');
}