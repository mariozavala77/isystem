var Timeout;
$(document).ready(function(){
    $('#search_product').keyup(function(){
        var keyCode = event.keyCode;
        if((keyCode<45 || keyCode>105) && keyCode!=8 && keyword!=46 && keyCode!=32){
            $('#search_product_reslut').hide();
            return false;
        }
        var keyword = $.trim($(this).val());
        if(!keyword){
            $('#search_product_reslut').hide();
            return false;
        }
        if(Timeout){
            $('#search_product_reslut').hide();
            clearTimeout(Timeout);
        }
        Timeout = setTimeout('search_product(\''+keyword+'\')',500);
    });
    $('#search_product_reslut').hover('',function(){
            $('#search_product_reslut').hide();
    });
    $('#mapping').click(function(){
        sku_mapping();
    });
});
function search_product(keyword){
    $.getJSON('/product/search',{keyword:keyword},function(result){
        if(result.status=='fail'){
            $.jGrowl(result.message);
            return false;
        }
        var result = result.message;
        var count = result.length;
        var html = [];
        html.push('<ul>');
        for (var i = 0; i < count; i++) {
            var product = result[i];
            html.push('<li data="');
            html.push(product.product_id);
            html.push('">');
            html.push(product.name);
            html.push('</li>');
        }
        html.push('</ul>');
        $('#search_product_reslut').html(html.join('')).show();
        $('#search_product_reslut ul li').hover(function(){
            $(this).addClass("highlighted");
        },function(){
            $(this).removeClass("highlighted");
        }).click(function(){
            $('#search_product').val($(this).text()).attr('data', $(this).attr('data'));
            $('#search_product_reslut').hide();
        });
    });
}
// 绑定sku
function sku_mapping(){
    var product_id = $('#search_product').attr('data');
    if(!product_id){
        $.jGrowl('请选择需要绑定的产品');
        return false;
    }
    var sku = datainfo.sku;
    $.post('/product/sale/mapping',{product_id:product_id,sku:sku,task_id:task_id},function(result){
        if(result.status=='fail'){
            $.jGrowl(result.message);
            return false;
        }
        location.reload();
    },'json');
}