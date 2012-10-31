<?php
/**
 * 用户组管理
 *
 * @author: weelion <weelion@qq.com>
 * @copyright: Copyright (c) 2012 EIMO Tech All Rights Reserved.
 * @version: $Id:group.php  2012年10月29日 星期一 12时52分15秒Z $
 */
class User_Group_Controller extends Base_Controller {

    // 用户组列表
    public function action_index() {
    
        return View::make('user.group.list'); 
    }

    // 用户组列表
    public function action_filter() {
        $fields = [ 'name', 'id' ];
        $groups = Group::filter( $fields );

        $data = Datatables::of($groups)->make();

        return Response::json( $data );
    }

    // 用户组添加
    public function action_add() {
    
        $permissions = Permission::get();
        return View::make('user.group.add')->with('permissions', $permissions);
    }

    // 用户组添加处理
    public function action_insert() {
        $name = Input::get('name');
        $permissions = Input::get('permission');

        Group::insert($name, $permissions);
    }

    // 用户组更新页面
    public function action_edit() {
        $group_id = Input::get('group_id');
        $rules    = Permission::get();
        $group    = Group::get( $group_id );

        return View::make('user.group.edit')->with('rules', $rules)
                                            ->with('group', $group);
    }

    // 用户组更新处理
    public function action_update() {
        $group_id    = Input::get('group_id');
        $name        = Input::get('name');
        $permissions = Input::get('permission');

        Group::update($group_id, $name, $permissions);

        return Redirect::to('user/group/edit?group_id='.$group_id);
    }

    // 用户组删除
    public function action_delete() {
        $group_id = Input::get('group_id');

        echo $group_id;die;
        $result = Group::delete($group_id);

        return Response::json($result);
    }

}
?>
