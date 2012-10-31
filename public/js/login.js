$(function(){

    // 登录前端验证
    $("#login").validate({
        rules: {
            username: "required",
            password: "required",
        },
        messages: {
            username: {required: '帐号不能为空'},
            password: {required: '密码不能为空'},
        },
        submitHandler: function() { // 登录
            var username = $('#username').val();
            var password = $('#password').val();
            var remember = $('#remember').attr('checked') == 'checked' ? 1 : 0;
            $.ajax({
                type: 'POST',
                data: {username: username, password: password, remember: remember},
                url: '/account/do_login',
                success: function( data ) {
                    if(data == 'login_fail') {
                        $.jGrowl('帐号或密码不正确');
                    } else if( data == 'login_success') {
                        $.jGrowl('登录成功');
                        self.location='/';
                    } else {
                        $.jGrowl('未知错误');
                    }
                },
                error: function() {
                    $.jGrowl('内部错误');
                }
            });
        }
    });

});
