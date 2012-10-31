<?php

/**
 * 用户组模型
 *
 * @author: weelion <weelion@qq.com>
 * @copyright: Copyright (c) 2012 EIMO Tech All Rights Reserved.
 * @version: $Id:Group.php  2012年10月30日 星期二 14时39分40秒Z $
 */
class Group {

    /**
     * 获取用户组权限
     *
     * @param: $group_id integer 用户组ID
     *
     * return array
     */
    public static function get( $group_id ) {
        $group = Sentry::group($group_id);
        $group->permissions = array_keys(json_decode($group->permissions, true));

        return $group;
    }

    /**
     * 用户组列表
     *
     * @param: $fields array 字段
     * @param: $filter array 附加筛选条件
     *
     * return object
     */
    public static function filter( $fields, $filter = [] ) {
        return DB::table('groups')->select( $fields );
    }


    /**
     * 添加用户组
     *
     * @param: $name       string 用户组名称
     * @param: $permission array  权限
     *
     * return boolean
     */
    public static function insert( $name, $permissions ) {

        if( !Sentry::group_exists($name) ) {
            $data = ['name' => $name, 'permissions' => json_encode($permissions) ];

            return Sentry::group()->create( $data );
        }

        return false;
    }

    /**
     * 更新用户组
     *
     * @param: $group_id    integer 用户组ID
     * @param: $name        sting   组名
     * @param: $permissions array   权限
     *
     * return boolean
     */
    public static function update( $group_id, $name, $permissions ) {
        $rules = Permission::get();
        $permissions = array_keys($permissions);

        $new_permissions = [];
        foreach($rules as $rule) {
            if(in_array($rule->rule, $permissions)) {
                $new_permissions[$rule->rule] = 1;
            } else {
                $new_permissions[$rule->rule] = 0;
            }
        }

        return Sentry::group($group_id)->update_permissions( $new_permissions );
    }

    /**
     * 删除用户组
     *
     * @param: $group_id integer 用户组ID
     *
     * return array
     */
    public static function delete( $group_id ) {
        $result = ['status' => 'success', 'message' => ''];

        try {
            if(!Sentry::group($group_id)->delete()) {
                $result = ['status' => 'fail', 'message' => '删除失败'];
            }
        } catch(Sentry\SentryException $e) {

            $result = ['status' => 'fail', 'message' => $e->getMessage() ];
        }

        return $result;
    }
}
?>
