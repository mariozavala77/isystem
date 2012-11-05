<?php

/**
 * 权限模型
 *
 * @author: weelion <weelion@qq.com>
 * @copyright: Copyright (c) 2012 EIMO Tech All Rights Reserved.
 * @version: $Id:Permission.php  2012年10月29日 星期一 15时09分28秒Z $
 */
class Permission {

    /**
     * 获取权限
     *
     * 获取指定权限没ID时获取所有权限
     *
     * @param integer $permission_id 权限ID
     *
     * return object 
     */
    public static function get( $permission_id = 0) {
        if( !empty($permission_id) ) {
            $permission = DB::table('rules')->where('id', '=', $permission_id)
                                            ->first();
        } else {
            $permission = DB::table('rules')->get();
        }

        return $permission;
    }

    /**
     * 权限列表
     *
     * @param: $fields array 字段
     * @param: $filter array 过滤
     *
     * return object
     */
    public static function filter( $fields, $filter = [] ) {
        return DB::table('rules')->select( $fields );
    }

    /**
     * 添加权限
     *
     * @param: $data array 权限数据
     *
     * return void
     */
    public static function insert( $data ) {
        DB::table('rules')->insert($data);;
    }

    /**
     * 更新权限
     *
     * @param: $permission_id integer 权限ID
     * @param: $data          array   数据
     *
     * return void
     */
    public static function update( $permission_id, $data ) {
        DB::table('rules')->where('id', '=', $permission_id)
                          ->update( $data );
    }

    /**
     * 删除权限
     *
     * @param: $permission_id integer 权限ID
     *
     * return array
     */
    public static function delete( $permission_id ) {

        $affected = DB::table('rules')->where('id', '=', $permission_id)
                                    ->delete(); 

        if($affected) {
            $result = [ 'status' => 'success', 'message' => '' ];
        } else {
            $result = [ 'status'  => 'fail', 'message' => '' ];
        }

        return $result;
    }

}
?>
