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
     * 获取所有权限
     *
     * return object 
     */
    public static function get() {
        return DB::table('rules')->get();
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

}
?>
