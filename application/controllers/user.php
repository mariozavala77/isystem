<?php
/**
 * 用户管理
 *
 * @author: weelion <weelion@qq.com>
 * @copyright: Copyright (c) 2012 EIMO Tech All Rights Reserved.
 * @version: $Id:user.php  2012年10月26日 星期五 09时52分48秒Z $
 */
class User_Controller extends Base_Controller {

    // 用户列表
    public function action_index() {
    
        return View::make('user.list');
    }

    // 用户列表
    public function action_filter() {

        $fields = [ 'username', 'email', 'ip_address', 'activated', 'last_login', 'id' ];
        $users = User::filter($fields);
        $data = Datatables::of( $users )->make();

        return Response::json( $data );
    }

    // 添加用户
    public function action_add() {
        $groups = Group::get();

        return View::make('user.add')->with('groups', $groups);
    }

    // 添加用户
    public function action_insert() {
    
    }

    // 编辑用户
    public function action_edit() {
    
    }

    // 更新用户
    public function action_update() {
    
    }

    // 删除用户
    public function action_delete() {
    
    }

    // 激活用户
    public function action_activate() {
    
    }
}
?>
