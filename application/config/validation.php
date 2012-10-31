<?php
return [
    // 登录表单验证
    'login' => [
        'username' => 'required',
        'password' => 'required',
    ],
    // 权限验证
    'permission' => [
        'description' => 'required',
        'rule'        => 'required'
    ],
];
?>
