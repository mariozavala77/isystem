<?php

/**
 * 任务模型
 *
 * @author: william <377658@qq.com>
 * @copyright: Copyright (c) 2012 UFCEC Tech All Rights Reserved.
 * @version: $Id:Tasks.php  2012年11月06日 星期二 17时12分47秒Z $
 */
class Tasks {

    /**
     * 列表
     *
     * @param: $fields array 字段
     *
     * return object
     */
    public static function filter($fields) {
        return DB::table('tasks')->select($fields);
    }
}

?>
