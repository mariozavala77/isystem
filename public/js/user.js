$(function() {

    // 编辑用户
    $('a.edit').live('click', function(){
        var id = $(this).attr('data-id');
        alert('edit' + id);
    });

    // 编辑用户
    $('a.delete').live('click', function(){
        alert('delete');
    });

    // 查看用户详情
    $('a.info').live('click', function(){
        alert('info');
    });

});
